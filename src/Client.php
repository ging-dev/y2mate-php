<?php

namespace Gingdev\Y2mate;

use EventSauce\ObjectHydrator\DefinitionProvider;
use EventSauce\ObjectHydrator\KeyFormatterWithoutConversion;
use EventSauce\ObjectHydrator\ObjectMapper;
use EventSauce\ObjectHydrator\ObjectMapperUsingReflection;
use Gingdev\Y2mate\Exceptions\InvalidArgumentException;
use Gingdev\Y2mate\Media\Media;
use Gingdev\Y2mate\Results\AnalyzeResult;
use Gingdev\Y2mate\Results\TaskResult;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client
{
    public const BASE_URL = 'https://en.y2mate.is';

    private function __construct(
        private HttpBrowser $browser,
        private ObjectMapper $objectMapper = new ObjectMapperUsingReflection(
            definitionProvider: new DefinitionProvider(
                keyFormatter: new KeyFormatterWithoutConversion(),
            ),
        ),
    ) {
        $crawler = $browser->request('GET', static::BASE_URL);
        $browser->setServerParameter(
            'HTTP_X_CSRF_TOKEN',
            $crawler->filterXPath('//*[@name="csrf-token"]')->attr('content')
        );
    }

    public static function create(?HttpClientInterface $client = null): self
    {
        return new self(new HttpBrowser($client ?: HttpClient::create()));
    }

    public function analyze(string $uri): AnalyzeResult
    {
        $this->browser->xmlHttpRequest('POST', self::BASE_URL.'/analyze', [
            'url' => $uri,
        ]);
        $json = $this->getReponse()->toArray();
        if ($json['error']) {
            throw new InvalidArgumentException($json['message']);
        }

        return $this->objectMapper->hydrateObject(AnalyzeResult::class, $json['formats']);
    }

    /**
     * @return \Generator<int, TaskResult, mixed, string>
     */
    public function createStreamedDownloadLink(Media $media): \Generator
    {
        $this->browser->xmlHttpRequest('POST', self::BASE_URL.'/convert', [
            'hash' => $media->hash,
        ]);
        $taskId = $this->getReponse()->toArray()['taskId'];
        do {
            $this->browser->xmlHttpRequest('POST', self::BASE_URL.'/task', [
                'taskId' => $taskId,
            ]);
            $task = $this->getReponse()->toArray();
            yield $this->objectMapper->hydrateObject(TaskResult::class, $task);
        } while (!isset($task['download']));

        return $task['download'];
    }

    public function createDownloadLink(Media $media): string
    {
        $stream = $this->createStreamedDownloadLink($media);
        while ($stream->valid()) {
            $stream->next();
        }

        return $stream->getReturn();
    }

    private function getReponse(): Response
    {
        return $this->browser->getResponse();
    }
}

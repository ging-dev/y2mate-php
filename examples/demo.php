<?php
use Gingdev\Y2mate\Client;

require __DIR__ . '/../vendor/autoload.php';

// composer require gingdev/y2mate

$client = Client::create();

$result = $client->analyze('https://www.youtube.com/watch?v=WPrhl0pFY_o');

echo $result->title, PHP_EOL;
echo $result->duration, PHP_EOL;

foreach ($result->audios as $audio) {
    echo 'Bitrate: ', $audio->bitrate, PHP_EOL;
    echo 'File size:', $audio->fileSize, PHP_EOL;
}

// Get download links
echo $client->createDownloadLink($result->audios[0]);

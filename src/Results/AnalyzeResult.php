<?php

namespace Gingdev\Y2mate\Results;

use EventSauce\ObjectHydrator\Constructor;
use Gingdev\Y2mate\Media\Audio;
use Gingdev\Y2mate\Media\Video;

class AnalyzeResult
{
    /**
     * @param Video[] $videos
     * @param Audio[] $audios
     */
    private function __construct(
        public string $id,
        public string $thumbnail,
        public string $title,
        public int $duration,
        public array $videos,
        public array $audios,
    ) {
    }

    /**
     * @param Video[] $video
     * @param Audio[] $audio
     */
    #[Constructor]
    public static function create(
        string $videoId,
        string $thumbnail,
        string $title,
        int $duration,
        array $video,
        array $audio,
    ): self {
        return new self(
            $videoId,
            $thumbnail,
            $title,
            $duration,
            $video,
            $audio,
        );
    }
}

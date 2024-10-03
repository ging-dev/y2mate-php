<?php

namespace Gingdev\Y2mate\Media;

use EventSauce\ObjectHydrator\Constructor;

class Video extends Media
{
    private function __construct(
        public string $fileType,
        public string $quality,
        public string $fileSize,
        public string $hash,
    ) {
    }

    #[Constructor]
    public static function create(
        string $fileType,
        string $quality,
        string $filesize,
        string $hash,
    ): self {
        return new self(
            $fileType,
            $quality,
            $filesize,
            $hash,
        );
    }
}

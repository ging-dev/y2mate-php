<?php

namespace Gingdev\Y2mate\ValueObjects;

use EventSauce\ObjectHydrator\Constructor;

class Audio extends Media
{
    private function __construct(
        public string $fileType,
        public int $bitrate,
        public string $fileSize,
        public string $hash,
    ) {
    }

    #[Constructor]
    public static function create(
        string $fileType,
        int $bitrate,
        string $filesize,
        string $hash,
    ): self {
        return new self(
            $fileType,
            $bitrate,
            $filesize,
            $hash,
        );
    }
}

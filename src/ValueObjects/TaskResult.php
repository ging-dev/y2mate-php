<?php

namespace Gingdev\Y2mate\ValueObjects;

use EventSauce\ObjectHydrator\Constructor;

final class TaskResult
{
    private function __construct(
        public string $status,
        public int $downloadProgress,
        public int $convertProgress,
    ) {
    }

    #[Constructor]
    public static function create(
        string $status,
        int $download_progress,
        int $convert_progress,
    ): self {
        return new self(
            $status,
            $download_progress,
            $convert_progress,
        );
    }
}

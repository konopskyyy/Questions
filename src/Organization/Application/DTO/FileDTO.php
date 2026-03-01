<?php

namespace App\Organization\Application\DTO;

class FileDTO
{
    public function __construct(
        public string $filename,
        public $content,
        public string $mimeType,
    ) {
    }
}

<?php

namespace App\Organization\Domain\Factory;

use App\Organization\Application\DTO\FileDTO;
use App\Organization\Domain\Entity\File;

class FileFactory
{
    public function createDto(File $file): FileDTO
    {
        return new FileDTO(
            filename: $file->getFilename(),
            content: $file->getContent(),
            mimeType: $file->getMimeType(),
        );
    }
}

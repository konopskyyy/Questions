<?php

namespace App\Organization\Application\Command\UploadOrganizationLogo\DTO;

use Symfony\Component\Uid\Uuid;

class UploadOrganizationLogoDTO
{
    public Uuid $id;

    public function __construct(
        public string $file,
        public string $mimeType,
    ) {
    }
}

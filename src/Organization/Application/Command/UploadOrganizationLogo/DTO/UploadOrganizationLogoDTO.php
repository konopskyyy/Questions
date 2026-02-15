<?php

namespace App\Organization\Application\Command\UploadOrganizationLogo\DTO;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class UploadOrganizationLogoDTO
{
    public Uuid $id;

    public function __construct(
        public string $file,
    ) {
    }
}

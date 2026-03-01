<?php

namespace App\Organization\Application\Command\UploadOrganizationLogo;

use App\Organization\Application\Command\UploadOrganizationLogo\DTO\UploadOrganizationLogoDTO;
use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
class UploadOrganizationLogoCommand
{
    public function __construct(
        public Uuid $organizationId,
        public UploadOrganizationLogoDTO $uploadOrganizationLogoDTO,
    ) {
    }
}

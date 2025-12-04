<?php

namespace App\Organization\Application\Command\CreateOrganization;

use App\Organization\Application\Command\CreateOrganization\DTO\CreateOrganizationDTO;
use Symfony\Component\Uid\Uuid;

class CreateOrganizationCommand
{
    public function __construct(
        public Uuid $organizationId,
        public CreateOrganizationDTO $createOrganizationDTO,
    ) {
    }
}

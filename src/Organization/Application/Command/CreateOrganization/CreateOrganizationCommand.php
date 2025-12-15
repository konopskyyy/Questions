<?php

namespace App\Organization\Application\Command\CreateOrganization;

use App\Organization\Application\Command\CreateOrganization\DTO\CreateOrganizationDTO;
use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
final readonly class CreateOrganizationCommand
{
    public function __construct(
        public CreateOrganizationDTO $createOrganizationDTO,
    ) {
    }
}

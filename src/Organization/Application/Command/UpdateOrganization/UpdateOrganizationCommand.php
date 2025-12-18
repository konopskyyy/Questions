<?php

namespace App\Organization\Application\Command\UpdateOrganization;

use App\Organization\Application\Command\UpdateOrganization\DTO\UpdateOrganizationDTO;
use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
class UpdateOrganizationCommand
{
    public function __construct(
        public Uuid $id,
        public UpdateOrganizationDTO $updateOrganizationDTO,
    ) {
    }
}

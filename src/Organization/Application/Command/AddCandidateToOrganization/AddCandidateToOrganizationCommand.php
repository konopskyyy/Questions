<?php

namespace App\Organization\Application\Command\AddCandidateToOrganization;

use App\Organization\Application\Command\AddCandidateToOrganization\DTO\AddCandidateToOrganizationDTO;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage]
final readonly class AddCandidateToOrganizationCommand
{
    public function __construct(
        public AddCandidateToOrganizationDTO $addCandidateToOrganizationDTO,
    ) {
    }
}

<?php

namespace App\Organization\Application\Command\LeaveCandidateOrganization;

use App\Organization\Application\Command\LeaveCandidateOrganization\DTO\LeaveCandidateOrganizationDTO;

class LeaveCandidateOrganizationCommand
{
    public function __construct(
        public LeaveCandidateOrganizationDTO $leaveCandidateOrganizationDTO,
    ) {
    }
}

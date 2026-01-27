<?php

namespace App\Organization\Application\Command\LeaveCandidateOrganization\DTO;

use Symfony\Component\Uid\Uuid;

class LeaveCandidateOrganizationDTO
{
    public function __construct(
        public readonly Uuid $organizationId,
        public readonly Uuid $candidateId,
    ) {
    }
}

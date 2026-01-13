<?php

namespace App\Organization\Application\Command\LeaveRecruiterOrganization\DTO;

use Symfony\Component\Uid\Uuid;

class LeaveRecruiterOrganizationDTO
{
    public function __construct(
        public readonly Uuid $organizationId,
        public readonly Uuid $recruiterId,
    ) {
    }
}

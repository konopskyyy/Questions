<?php

namespace App\Organization\Application\Command\LeaveRecruiterOrganization;

use App\Organization\Application\Command\LeaveRecruiterOrganization\DTO\LeaveRecruiterOrganizationDTO;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage]
class LeaveRecruiterOrganizationCommand
{
    public function __construct(
        public readonly LeaveRecruiterOrganizationDTO $leaveRecruiterOrganizationDTO,
    ) {
    }
}

<?php

namespace App\Organization\Application\Command\AddRecruiterToOrganization;

use App\Organization\Application\Command\AddRecruiterToOrganization\DTO\AddRecruiterToOrganizationDTO;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage]
final readonly class AddRecruiterToOrganizationCommand
{
    public function __construct(
        public AddRecruiterToOrganizationDTO $addRecruiterToOrganizationDTO,
    ) {
    }
}

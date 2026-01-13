<?php

namespace App\Organization\Application\Command\AddRecruiterToOrganization\DTO;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
class AddRecruiterToOrganizationDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public Uuid $recruiterId,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public Uuid $organizationId,
    ) {
    }
}

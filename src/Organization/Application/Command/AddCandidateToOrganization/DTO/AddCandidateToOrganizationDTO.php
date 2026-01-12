<?php

namespace App\Organization\Application\Command\AddCandidateToOrganization\DTO;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
class AddCandidateToOrganizationDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public Uuid $candidateId,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public Uuid $organizationId,
    ) {
    }
}

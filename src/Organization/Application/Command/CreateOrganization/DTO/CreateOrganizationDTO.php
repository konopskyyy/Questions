<?php

namespace App\Organization\Application\Command\CreateOrganization\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateOrganizationDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 255)]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 255)]
        public string $logo,

        public array $address,
        public array $recruiters,
    ) {
    }
}

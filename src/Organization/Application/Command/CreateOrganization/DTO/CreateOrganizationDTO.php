<?php

namespace App\Organization\Application\Command\CreateOrganization\DTO;

use App\Organization\Application\DTO\AddressDTO;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
class CreateOrganizationDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 255)]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 120)]
        public string $taxId,

        public AddressDTO $address,
        public array $recruiters,
    ) {
    }
}

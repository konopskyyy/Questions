<?php

namespace App\Organization\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;

class AddressDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Length(min: 3, max: 255)]
        public string $street,

        #[Assert\NotBlank]
        #[Length(min: 1, max: 5)]
        public string $buildingNo,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\When(
            expression: 'this.apartmentNo !== null',
            constraints: [
                new Length(min: 1, max: 5),
            ]
        )]
        public ?string $apartmentNo,

        #[Assert\NotBlank]
        #[Length(min: 3, max: 255)]
        public string $city,

        #[Assert\NotBlank]
        #[Length(min: 1, max: 6)]
        public string $postalCode,

        #[Assert\NotBlank]
        #[Length(min: 3, max: 255)]
        public string $country,
    ) {
    }

    public function __serialize(): array
    {
        return [
            'street' => $this->street,
            'buildingNo' => $this->buildingNo,
            'apartmentNo' => $this->apartmentNo,
            'city' => $this->city,
            'postalCode' => $this->postalCode,
            'country' => $this->country,
        ];
    }
}

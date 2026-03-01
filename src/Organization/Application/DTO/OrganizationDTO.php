<?php

namespace App\Organization\Application\DTO;

use Symfony\Component\Uid\Uuid;

class OrganizationDTO
{
    public function __construct(
        public Uuid $id,
        public string $name,
        public string $taxId,
        public AddressDTO $address,
        public \DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
    ) {
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'taxId' => $this->taxId,
            'address' => $this->address,
            'createdAt' => $this->createdAt->format(\DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt?->format(\DateTimeInterface::ATOM),
        ];
    }
}

<?php

namespace App\Organization\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', length: 36, unique: true)]
    private Uuid $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\Column(type: 'string', length: 255)]
        private string $street,

        #[ORM\Column(type: 'string', length: 5)]
        private string $buildingNo,

        #[ORM\Column(type: 'string', length: 5, nullable: true)]
        private ?string $apartmentNo,

        #[ORM\Column(type: 'string', length: 255)]
        private string $city,

        #[ORM\Column(type: 'string', length: 6)]
        private string $postalCode,

        #[ORM\Column(type: 'string', length: 255)]
        private string $country,
    ) {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getBuildingNo(): string
    {
        return $this->buildingNo;
    }

    public function getApartmentNo(): ?string
    {
        return $this->apartmentNo;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function setBuildingNo(string $buildingNo): self
    {
        $this->buildingNo = $buildingNo;

        return $this;
    }

    public function setApartmentNo(?string $apartmentNo): self
    {
        $this->apartmentNo = $apartmentNo;

        return $this;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    #[PrePersist]
    public function preCreate(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}

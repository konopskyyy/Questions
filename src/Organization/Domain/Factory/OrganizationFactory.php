<?php

namespace App\Organization\Domain\Factory;

use App\Organization\Application\DTO\AddressDTO;
use App\Organization\Application\DTO\OrganizationDTO;
use App\Organization\Domain\Entity\Organization;
use App\User\Domain\Repository\UserRepositoryInterface;

class OrganizationFactory
{
    public function __construct(
        private readonly AddressFactory $addressService,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function create(
        string $name,
        string $taxId,
        AddressDTO $addressDto,
        array $recruiters,
    ): Organization {
        $organization = new Organization(
            name: $name,
            address: $this->addressService->create(
                street: $addressDto->street,
                buildingNo: $addressDto->buildingNo,
                apartmentNo: $addressDto->apartmentNo,
                city: $addressDto->city,
                postalCode: $addressDto->postalCode,
                country: $addressDto->country,
            ),
            taxId: $taxId,
        );

        foreach ($recruiters as $recruiterId) {
            $recruiter = $this->userRepository->getById($recruiterId);
            $organization->addRecruiter($recruiter);
        }

        return $organization;
    }

    public function createDto(Organization $organization): OrganizationDTO
    {
        return new OrganizationDTO(
            id: $organization->getId(),
            name: $organization->getName(),
            taxId: $organization->getTaxId(),
            address: $this->addressService->createDto(
                address: $organization->getAddress(),
            ),
            createdAt: $organization->getCreatedAt(),
            updatedAt: $organization->getUpdatedAt(),
        );
    }
}

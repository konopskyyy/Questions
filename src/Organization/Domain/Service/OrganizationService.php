<?php

namespace App\Organization\Domain\Service;

use App\Organization\Domain\Entity\Organization;
use App\User\Domain\Repository\UserRepositoryInterface;

class OrganizationService
{
    public function __construct(
        private readonly AddressService $addressService,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function create(
        string $name,
        string $logo,
        array $address,
        array $recruiters,
    ): Organization {
        $organization = new Organization(
            name: $name,
            logo: $logo,
            address: $this->addressService->create(
                street: $address['street'],
                buildingNo: $address['buildingNo'],
                apartmentNo: $address['apartmentNo'],
                city: $address['city'],
                postalCode: $address['postalCode'],
                country: $address['country'],
            ),
        );

        foreach ($recruiters as $recruiterId) {
            $recruiter = $this->userRepository->findById($recruiterId);


        }

        return $organization;
    }
}

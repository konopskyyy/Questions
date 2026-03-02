<?php

namespace App\Organization\Application\Service;

use App\Organization\Application\Command\UpdateOrganization\DTO\UpdateOrganizationDTO;
use App\Organization\Domain\Entity\Organization;

class OrganizationUpdater
{
    public function update(Organization $organization, UpdateOrganizationDTO $command): void
    {
        $organization->setName($command->name);
        $organization->setTaxId($command->taxId);

        $this->updateAddress($organization, $command);
    }

    private function updateAddress(Organization $organization, UpdateOrganizationDTO $updateOrganizationDTO): void
    {
        $organization->getAddress()
            ->setStreet($updateOrganizationDTO->address->street)
            ->setCity($updateOrganizationDTO->address->city)
            ->setBuildingNo($updateOrganizationDTO->address->buildingNo)
            ->setApartmentNo($updateOrganizationDTO->address->apartmentNo)
            ->setCountry($updateOrganizationDTO->address->country)
            ->setPostalCode($updateOrganizationDTO->address->postalCode)
        ;
    }
}

<?php

namespace App\Organization\Domain\Factory;

use App\Organization\Application\DTO\AddressDTO;
use App\Organization\Domain\Entity\Address;

class AddressFactory
{
    public function create(
        string $street,
        string $buildingNo,
        ?string $apartmentNo,
        string $city,
        string $postalCode,
        string $country,
    ): Address {
        return new Address(
            street: $street,
            buildingNo: $buildingNo,
            apartmentNo: $apartmentNo,
            city: $city,
            postalCode: $postalCode,
            country: $country,
        );
    }

    public function createDto(Address $address): AddressDTO
    {
        return new AddressDTO(
            street: $address->getStreet(),
            buildingNo: $address->getBuildingNo(),
            apartmentNo: $address->getApartmentNo(),
            city: $address->getCity(),
            postalCode: $address->getPostalCode(),
            country: $address->getCountry(),
        );
    }
}

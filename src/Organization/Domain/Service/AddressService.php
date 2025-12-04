<?php

namespace App\Organization\Domain\Service;

use App\Organization\Domain\Entity\Address;

class AddressService
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
}

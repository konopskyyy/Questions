<?php

namespace App\Organization\Domain\Repository;

use App\Organization\Domain\Entity\Address;
use Symfony\Component\Uid\Uuid;

interface AddressRepositoryInterface
{
    public function save(Address $address): void;

    public function remove(Address $address): void;

    public function find(Uuid $id): ?Address;
}

<?php

namespace App\Organization\Infrastructure\Repository;

use App\Organization\Domain\Entity\Address;
use App\Organization\Domain\Repository\AddressRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Uuid;

class AddressRepository implements AddressRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Address::class);
    }

    public function save(Address $address): void
    {
        $this->entityManager->persist($address);
        $this->entityManager->flush();
    }

    public function remove(Address $address): void
    {
        $this->entityManager->remove($address);
        $this->entityManager->flush();
    }

    public function find(Uuid $id): ?Address
    {
        return $this->repository->findOneBy(['id' => $id]);
    }
}

<?php

namespace App\Organization\Infrastructure\Repository;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Uuid;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Organization::class);
    }

    public function save(Organization $organization): void
    {
        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }

    public function remove(Organization $organization): void
    {
        $this->entityManager->remove($organization);
        $this->entityManager->flush();
    }

    public function find(Uuid $id): ?Organization
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function findByTaxId(string $taxId): ?Organization
    {
        return $this->repository->findOneBy(['taxId' => $taxId]);
    }
}

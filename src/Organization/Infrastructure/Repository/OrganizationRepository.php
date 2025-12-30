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

    public function findByRecruiterId(Uuid $recruiterId): ?Organization
    {
        return $this->repository->createQueryBuilder('organization')
            ->join('organization.recruiters', 'recruiter')
            ->andWhere('recruiter.id = :recruiterId')
            ->setParameter('recruiterId', $recruiterId->toBinary())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByCandidateId(Uuid $candidateId): ?Organization
    {
        return $this->repository->createQueryBuilder('o')
            ->join('o.candidates', 'c')
            ->andWhere('c.id = :candidateId')
            ->setParameter('candidateId', $candidateId->toBinary())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}

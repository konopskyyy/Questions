<?php

namespace App\Organization\Infrastructure\Repository;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Enum\OrganizationRole;
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

    public function remove(Organization $organization, ?bool $forceFlush = false): void
    {
        $this->entityManager->remove($organization);
        if ($forceFlush) {
            $this->entityManager->flush();
        }
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
        return $this->findByUserIdAndRole($recruiterId, OrganizationRole::RECRUITER);
    }

    public function findByCandidateId(Uuid $candidateId): ?Organization
    {
        return $this->findByUserIdAndRole($candidateId, OrganizationRole::CANDIDATE);
    }

    public function findOrganizationByUserId(Uuid $userId): ?Organization
    {
        return $this->repository
            ->createQueryBuilder('organization')
            ->join('organization.memberships', 'membership')
            ->join('membership.user', 'user')
            ->andWhere('user.id = :userId')
            ->setParameter('userId', $userId->toBinary())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByUserIdAndRole(Uuid $userId, OrganizationRole $role): ?Organization
    {
        return $this->repository
            ->createQueryBuilder('organization')
            ->join('organization.memberships', 'membership')
            ->join('membership.user', 'user')
            ->andWhere('user.id = :userId')
            ->andWhere('membership.role = :role')
            ->setParameter('userId', $userId->toBinary())
            ->setParameter('role', $role->value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllByUserId(Uuid $userId): array
    {
        return $this->repository
            ->createQueryBuilder('organization')
            ->join('organization.memberships', 'membership')
            ->join('membership.user', 'user')
            ->where('user.id = :userId')
            ->setParameter('userId', $userId->toBinary())
            ->getQuery()
            ->getResult()
        ;
    }
}

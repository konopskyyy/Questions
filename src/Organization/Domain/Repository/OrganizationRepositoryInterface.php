<?php

namespace App\Organization\Domain\Repository;

use App\Organization\Domain\Entity\Organization;
use Symfony\Component\Uid\Uuid;

interface OrganizationRepositoryInterface
{
    public function save(Organization $organization): void;

    public function remove(Organization $organization): void;

    public function find(Uuid $id): ?Organization;

    public function findByTaxId(string $taxId): ?Organization;

    public function findByRecruiterId(Uuid $recruiterId): ?Organization;
    public function findByCandidateId(Uuid $candidateId): ?Organization;
}

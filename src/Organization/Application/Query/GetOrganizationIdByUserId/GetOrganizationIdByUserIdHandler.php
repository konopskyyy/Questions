<?php

namespace App\Organization\Application\Query\GetOrganizationIdByUserId;

use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
class GetOrganizationIdByUserIdHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(GetOrganizationIdByUserIdQuery $query): ?Uuid
    {
        $organization = $this->organizationRepository->find($query->userId);

        return $organization?->getId() ?? null;
    }
}

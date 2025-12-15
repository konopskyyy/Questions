<?php

namespace App\Organization\Application\Query\GetOrganizationById;

use App\Organization\Application\DTO\OrganizationDTO;
use App\Organization\Application\Exception\OrganizationNotFoundException;
use App\Organization\Domain\Factory\OrganizationFactory;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetOrganizationByIdHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly OrganizationFactory $organizationFactory,
    ) {
    }

    public function __invoke(GetOrganizationByIdQuery $query): OrganizationDTO
    {
        $organization = $this->organizationRepository->find($query->id);

        if (!$organization) {
            throw new OrganizationNotFoundException($query->id->toString());
        }

        return $this->organizationFactory->createDto($organization);
    }
}

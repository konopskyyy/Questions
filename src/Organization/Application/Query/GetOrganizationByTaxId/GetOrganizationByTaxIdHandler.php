<?php

namespace App\Organization\Application\Query\GetOrganizationByTaxId;

use App\Organization\Application\DTO\OrganizationDTO;
use App\Organization\Application\Exception\OrganizationNotFoundException;
use App\Organization\Domain\Factory\OrganizationFactory;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetOrganizationByTaxIdHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly OrganizationFactory $organizationFactory,
    ) {
    }

    public function __invoke(GetOrganizationByTaxIdQuery $query): OrganizationDTO
    {
        $organization = $this->organizationRepository->findByTaxId($query->taxId);

        if (!$organization) {
            throw new OrganizationNotFoundException();
        }

        return $this->organizationFactory->createDto($organization);
    }
}

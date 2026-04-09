<?php

namespace App\Organization\Application\Query\GetOrganizationCollectionByUserId;

use App\Organization\Application\DTO\OrganizationDTO;
use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Factory\OrganizationFactory;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetOrganizationCollectionByUserIdHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly OrganizationFactory $organizationFactory,
    ) {
    }

    public function __invoke(GetOrganizationCollectionByUserIdQuery $query): Collection
    {
        $organizations = $this->organizationRepository->findAllByUserId($query->userId);

        /** @var ArrayCollection<int, OrganizationDTO> $organizationsDtoCollection */
        $organizationsDtoCollection = new ArrayCollection();

        /** @var Organization $organization */
        foreach ($organizations as $organization) {
            $organizationsDtoCollection->add($this->organizationFactory->createDto($organization));
        }

        return $organizationsDtoCollection;
    }
}

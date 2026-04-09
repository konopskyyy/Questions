<?php

namespace App\Organization\Application\Query\GetOrganizationLogo;

use App\Organization\Application\DTO\FileDTO;
use App\Organization\Application\Exception\LogoNotFoundException;
use App\Organization\Application\Exception\OrganizationNotFoundException;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetOrganizationLogoHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
    ) {
    }

    public function __invoke(GetOrganizationLogoQuery $query): FileDTO
    {
        $organization = $this->organizationRepository->find($query->organizationId);

        if (!$organization) {
            throw new OrganizationNotFoundException($query->organizationId->toString());
        }

        $logo = $organization->getLogo();

        if (!$logo) {
            throw new LogoNotFoundException($query->organizationId->toString());
        }

        return new FileDTO(
            filename: $logo->getFilename(),
            content: $logo->getContent(),
            mimeType: $logo->getMimeType(),
        );
    }
}

<?php

namespace App\Organization\Application\Command\RemoveOrganization;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RemoveOrganizationHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(RemoveOrganizationCommand $command): void
    {
        /** @var Organization $organization */
        $organization = $this->organizationRepository->find($command->id);

        $this->organizationRepository->remove($organization);

        $this->logger->info(
            message: '[RemoveOrganization] Organization was removed.',
            context: [
                'organization_id' => $command->id->toString(),
                'organization_name' => $organization->getName(),
            ],
        );
    }
}

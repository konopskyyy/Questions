<?php

namespace App\Organization\Application\Command\UpdateOrganization;

use App\Organization\Application\Service\OrganizationUpdater;
use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateOrganizationHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly OrganizationUpdater $organizationUpdater,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(UpdateOrganizationCommand $command): void
    {
        /** @var Organization $organization */
        $organization = $this->organizationRepository->find($command->id);

        $this->organizationUpdater->update($organization, $command->updateOrganizationDTO);

        $this->organizationRepository->save($organization);

        $this->logger->info(
            message: '[UpdateOrganization] Organization was successfully updated.',
            context: [
                'organization_id' => $organization->getId()->toString(),
            ],
        );
    }
}

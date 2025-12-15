<?php

namespace App\Organization\Application\Command\CreateOrganization;

use App\Common\Attribute\AsMessageValidator;
use App\Common\Exception\ValidationFail;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Psr\Log\LoggerInterface;

#[AsMessageValidator]
class CreateOrganizationValidator
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(CreateOrganizationCommand $command): void
    {
        $organization = $this->organizationRepository->findByTaxId($command->createOrganizationDTO->taxId);

        if ($organization) {
            $this->logger->info('[CreateOrganization] Organization already exists.', [
                'organization_id' => $organization->getId()->toString(),
                'organization_name' => $organization->getName(),
            ]);

            throw new ValidationFail('Organization already exists.');
        }
    }
}

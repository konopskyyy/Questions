<?php

namespace App\Organization\Application\Command\UploadOrganizationLogo;

use App\Common\Attribute\AsMessageValidator;
use App\Organization\Application\Exception\OrganizationNotFoundException;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Psr\Log\LoggerInterface;

#[AsMessageValidator]
class UploadOrganizationLogoValidator
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(UploadOrganizationLogoCommand $command): void
    {
        $organization = $this->organizationRepository->find($command->organizationId);

        if (!$organization) {
            $this->logger->info(
                message: '[UploadOrganizationLogoValidator] Organization not found',
                context: [
                    'organization_id' => $command->organizationId,
                ],
            );

            throw new OrganizationNotFoundException();
        }
    }
}

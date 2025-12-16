<?php

namespace App\Organization\Application\Command\RemoveOrganization;

use App\Common\Attribute\AsMessageValidator;
use App\Common\Exception\ValidationFail;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Psr\Log\LoggerInterface;

#[AsMessageValidator]
class RemoveOrganizationValidator
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(RemoveOrganizationCommand $command): void
    {
        $organization = $this->organizationRepository->find($command->id);

        if (!$organization) {
            $this->logger->info(
                message: '[RemoveOrganization] Organization not found',
                context: [
                    'organizationId' => $command->id,
                ],
            );

            throw new ValidationFail('Organization not found');
        }
    }
}

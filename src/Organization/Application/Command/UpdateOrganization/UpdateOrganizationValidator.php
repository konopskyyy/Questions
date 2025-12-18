<?php

namespace App\Organization\Application\Command\UpdateOrganization;

use App\Common\Attribute\AsMessageValidator;
use App\Common\Exception\ValidationFail;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Psr\Log\LoggerInterface;

#[AsMessageValidator]
class UpdateOrganizationValidator
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(UpdateOrganizationCommand $command): void
    {
        $organization = $this->organizationRepository->find($command->id);

        if (!$organization) {
            $this->logger->info(
                message: '[UpdateOrganization] Organization not found',
                context: [
                    'organization_id' => $command->id->toString(),
                ],
            );

            throw new ValidationFail(message: 'Organization not found');
        }
    }
}

<?php

namespace App\Organization\Application\Command\CreateOrganization;

use App\Common\Attribute\AsMessageValidator;
use App\Common\Exception\ValidationFail;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

#[AsMessageValidator]
class CreateOrganizationValidator
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly UserRepositoryInterface $userRepository,
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

        $user = $this->userRepository->findById($command->userId);

        if (!$user) {
            $this->logger->info('[CreateOrganization] User not found.', [
                'user_id' => $command->userId->toString(),
            ]);

            throw new ValidationFail('User not found.');
        }

        if ($this->userRepository->isUserInAnotherOrganization($user)) {
            $this->logger->info('[CreateOrganization] User is already in an organization.', [
                'user_id' => $user->getId()?->toString(),
            ]);

            throw new ValidationFail('User is already in an organization.');
        }
    }
}

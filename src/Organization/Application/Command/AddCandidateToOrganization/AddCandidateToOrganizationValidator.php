<?php

namespace App\Organization\Application\Command\AddCandidateToOrganization;

use App\Common\Attribute\AsMessageValidator;
use App\Common\Exception\ValidationFail;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

#[AsMessageValidator]
class AddCandidateToOrganizationValidator
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(AddCandidateToOrganizationCommand $command): void
    {
        $organization = $this->organizationRepository->find($command->addCandidateToOrganizationDTO->organizationId);

        if (!$organization) {
            $this->logger->info(
                message: '[AddRecruiterToOrganizationCommand] Organization not found',
                context: [
                    'organization_id' => $command->addCandidateToOrganizationDTO->organizationId,
                ],
            );
            throw new ValidationFail('Organization not found');
        }

        $user = $this->userRepository->findById($command->addCandidateToOrganizationDTO->candidateId);

        if (!$user) {
            $this->logger->info(
                message: '[AddRecruiterToOrganizationCommand] User not found',
                context: [
                    'user_id' => $command->addCandidateToOrganizationDTO->candidateId,
                ],
            );
            throw new ValidationFail('User not found');
        }
    }
}

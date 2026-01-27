<?php

namespace App\Organization\Application\Command\LeaveRecruiterOrganization;

use App\Common\Attribute\AsMessageValidator;
use App\Common\Exception\ValidationFail;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

#[AsMessageValidator]
class LeaveRecruiterOrganizationValidator
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(LeaveRecruiterOrganizationCommand $command): void
    {
        $organization = $this->organizationRepository->find($command->leaveRecruiterOrganizationDTO->organizationId);

        if (!$organization) {
            $this->logger->info(
                message: '[LeaveRecruiterOrganization] Organization not found',
                context: [
                    'organization_id' => $command->leaveRecruiterOrganizationDTO->organizationId,
                ],
            );
            throw new ValidationFail('Organization not found');
        }

        $user = $this->userRepository->findById($command->leaveRecruiterOrganizationDTO->recruiterId);

        if (!$user) {
            $this->logger->info(
                message: '[LeaveRecruiterOrganization] User not found',
                context: [
                    'user_id' => $command->leaveRecruiterOrganizationDTO->recruiterId,
                ],
            );
            throw new ValidationFail('User not found');
        }
    }
}

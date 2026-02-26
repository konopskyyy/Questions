<?php

namespace App\Organization\Application\Command\LeaveRecruiterOrganization;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LeaveRecruiterOrganizationHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(LeaveRecruiterOrganizationCommand $command): void
    {
        /** @var Organization $organization */
        $organization = $this->organizationRepository->find($command->leaveRecruiterOrganizationDTO->organizationId);

        $recruiter = $this->userRepository->getById($command->leaveRecruiterOrganizationDTO->recruiterId);

        $organization->removeRecruiter($recruiter);

        if (0 === sizeof($organization->getRecruiters())) {
            $this->organizationRepository->remove($organization);
        }

        $this->organizationRepository->save($organization);

        $this->logger->info(
            message: '[LeaveRecruiterOrganization] Recruiter leaved successfully',
            context: [
                'organization_id' => $command->leaveRecruiterOrganizationDTO->organizationId,
                'recruiter_id' => $command->leaveRecruiterOrganizationDTO->recruiterId,
            ],
        );
    }
}

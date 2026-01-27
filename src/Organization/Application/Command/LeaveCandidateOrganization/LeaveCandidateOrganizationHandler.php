<?php

namespace App\Organization\Application\Command\LeaveCandidateOrganization;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LeaveCandidateOrganizationHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(LeaveCandidateOrganizationCommand $command): void
    {
        /** @var Organization $organization */
        $organization = $this->organizationRepository->find($command->leaveCandidateOrganizationDTO->organizationId);

        $candidate = $this->userRepository->getById($command->leaveCandidateOrganizationDTO->candidateId);

        $organization->removeCandidate($candidate);

        $this->userRepository->remove($candidate);

        $this->organizationRepository->save($organization);

        $this->logger->info(
            message: '[LeaveCandidateOrganization] Recruiter leaved successfully',
            context: [
                'organization_id' => $command->leaveCandidateOrganizationDTO->organizationId,
                'candidate_id' => $command->leaveCandidateOrganizationDTO->candidateId,
            ],
        );
    }
}

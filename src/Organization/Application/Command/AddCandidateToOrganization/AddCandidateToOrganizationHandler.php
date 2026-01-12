<?php

namespace App\Organization\Application\Command\AddCandidateToOrganization;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddCandidateToOrganizationHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(AddCandidateToOrganizationCommand $command): void
    {
        /** @var Organization $organization */
        $organization = $this->organizationRepository->find($command->addCandidateToOrganizationDTO->organizationId);

        $candidate = $this->userRepository->getById($command->addCandidateToOrganizationDTO->candidateId);

        $organization->addCandidate($candidate);

        $this->organizationRepository->save($organization);

        $this->logger->info(
            message: '[AddRecruiterToOrganization] Added user to organization',
            context: [
                'organization_id' => $command->addCandidateToOrganizationDTO->organizationId,
                'candidate_id' => $command->addCandidateToOrganizationDTO->candidateId,
            ],
        );
    }
}

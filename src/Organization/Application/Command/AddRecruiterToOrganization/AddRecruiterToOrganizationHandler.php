<?php

namespace App\Organization\Application\Command\AddRecruiterToOrganization;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddRecruiterToOrganizationHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(AddRecruiterToOrganizationCommand $command): void
    {
        /** @var Organization $organization */
        $organization = $this->organizationRepository->find($command->addRecruiterToOrganizationDTO->organizationId);

        $recruiter = $this->userRepository->getById($command->addRecruiterToOrganizationDTO->recruiterId);

        $organization->addRecruiter($recruiter);

        $this->organizationRepository->save($organization);

        $this->logger->info(
            message: '[AddRecruiterToOrganization] Added user to organization',
            context: [
                'organization_id' => $command->addRecruiterToOrganizationDTO->organizationId,
                'recruiter_id' => $command->addRecruiterToOrganizationDTO->recruiterId,
            ],
        );
    }
}

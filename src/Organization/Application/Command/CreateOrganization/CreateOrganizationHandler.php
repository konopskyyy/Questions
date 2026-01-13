<?php

namespace App\Organization\Application\Command\CreateOrganization;

use App\Organization\Domain\Factory\OrganizationFactory;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateOrganizationHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly OrganizationFactory $organizationFactory,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(CreateOrganizationCommand $command): void
    {
        $organization = $this->organizationFactory->create(
            name: $command->createOrganizationDTO->name,
            logo: $command->createOrganizationDTO->logo,
            taxId: $command->createOrganizationDTO->taxId,
            addressDto: $command->createOrganizationDTO->address,
            recruiters: $command->createOrganizationDTO->recruiters,
        );

        /** @var User $user */
        $user = $this->userRepository->findById(
            id: $command->userId,
        );

        $organization->addRecruiter(
            user: $user,
        );

        $this->organizationRepository->save($organization);

        $this->logger->info('[CreateOrganization] Organization created.', [
            'organizationId' => $organization->getId()->toString(),
        ]);
    }
}

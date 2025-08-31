<?php

declare(strict_types=1);

namespace App\User\Application\Deactivate;

use App\User\Domain\User;
use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DeactivateUserCommandHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {

    }
    public function __invoke(DeactivateUserCommand $command): void
    {
        /** @var User $user */
        $user = $this->userRepository->findById($command->userId);

        $user->deactivate();
        $this->userRepository->save($user, true);
    }
}

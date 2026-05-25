<?php

declare(strict_types=1);

namespace App\User\Application\ResetPassword;

use App\User\Domain\Event\UserPasswordChangedEvent;
use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class ResetPasswordCommandHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(ResetPasswordCommand $command): void
    {
        $user = $this->userRepository->findByEmail($command->email);
        if (!$user) {
            return;
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $command->password));
        $this->userRepository->save($user);

        $this->eventDispatcher->dispatch(new UserPasswordChangedEvent($user));
    }
}

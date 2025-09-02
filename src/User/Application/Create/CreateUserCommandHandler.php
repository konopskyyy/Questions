<?php

declare(strict_types=1);

namespace App\User\Application\Create;

use App\User\Domain\User;
use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class CreateUserCommandHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $user = new User();
        $user->setEmail($command->email)
            ->setRoles(['ROLE_ADMIN'])
            ->setIsActive(true)
        ;

        $user->setPassword($this->passwordHasher->hashPassword($user, $command->password));

        $this->userRepository->save($user, true);
    }
}

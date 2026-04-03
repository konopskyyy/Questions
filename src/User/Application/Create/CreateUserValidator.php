<?php

declare(strict_types=1);

namespace App\User\Application\Create;

use App\Common\Attribute\AsMessageValidator;
use App\User\Domain\Validation\UserAlreadyExistsValidation;
use App\User\Domain\Validation\ValidationContextFactory;
use App\User\Infrastructure\Repository\UserRepository;

#[AsMessageValidator]
class CreateUserValidator
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ValidationContextFactory $validationContextFactory,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $user = $this->userRepository->findByEmail($command->email);

        $context = $this->validationContextFactory->create(self::class, $command->email);
        (new UserAlreadyExistsValidation())->validate($user, $context);
    }
}

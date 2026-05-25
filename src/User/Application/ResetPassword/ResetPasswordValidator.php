<?php

declare(strict_types=1);

namespace App\User\Application\ResetPassword;

use App\Common\Attribute\AsMessageValidator;
use App\User\Domain\Validation\UserNotFoundValidation;
use App\User\Domain\Validation\ValidationContextFactory;
use App\User\Infrastructure\Repository\UserRepository;

#[AsMessageValidator]
class ResetPasswordValidator
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ValidationContextFactory $validationContextFactory,
    ) {
    }

    public function __invoke(ResetPasswordCommand $command): void
    {
        $user = $this->userRepository->findByEmail($command->email);

        $context = $this->validationContextFactory->create(self::class, $command->email);
        (new UserNotFoundValidation())->validate($user, $context);
    }
}

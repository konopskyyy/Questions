<?php

declare(strict_types=1);

namespace App\User\Application\Deactivate;

use App\Common\Attribute\AsMessageValidator;
use App\User\Domain\User;
use App\User\Domain\Validation\UserInactiveValidation;
use App\User\Domain\Validation\UserNotFoundValidation;
use App\User\Domain\Validation\UserValidationInterface;
use App\User\Infrastructure\Repository\UserRepository;

#[AsMessageValidator]
class DeactivateUserCommandValidator
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(DeactivateUserCommand $command): void
    {
        /** @var User $user */
        $user = $this->userRepository->findById($command->userId);

        $validations = [
            new UserNotFoundValidation(),
            new UserInactiveValidation(),
        ];

        /** @var UserValidationInterface $validation */
        foreach ($validations as $validation) {
            $validation->validate($user);
        }
    }
}

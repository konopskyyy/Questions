<?php

declare(strict_types=1);

namespace User\Application\ResetPassword;

use App\User\Application\ResetPassword\ResetPasswordCommand;
use App\User\Application\ResetPassword\ResetPasswordCommandValidator;
use App\User\Domain\User;
use App\User\Domain\Validation\ValidationContextFactory;
use App\User\Infrastructure\Repository\UserRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ResetPasswordCommandValidatorTest extends TestCase
{
    private UserRepository $userRepository;
    private ValidationContextFactory $validationContextFactory;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->validationContextFactory = new ValidationContextFactory();
    }

    #[Test]
    public function shouldThrowExceptionWhenUserIsNotFound(): void
    {
        $email = 'nonexistent@example.com';
        $this->userRepository->method('findByEmail')->with($email)->willReturn(null);

        $validator = new ResetPasswordCommandValidator(
            $this->userRepository,
            $this->validationContextFactory
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('U01: User was not found.');

        $validator(new ResetPasswordCommand($email, 'password'));
    }

    #[Test]
    public function shouldPassValidationWhenUserExists(): void
    {
        $email = 'exists@example.com';
        $user = new User();
        $this->userRepository->method('findByEmail')->with($email)->willReturn($user);

        $validator = new ResetPasswordCommandValidator(
            $this->userRepository,
            $this->validationContextFactory
        );

        $validator(new ResetPasswordCommand($email, 'password'));
        $this->assertTrue(true); // Assert no exception is thrown
    }
}

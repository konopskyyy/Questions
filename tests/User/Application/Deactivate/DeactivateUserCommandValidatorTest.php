<?php

declare(strict_types=1);

namespace User\Application\Deactivate;

use App\User\Application\Deactivate\DeactivateUserCommand;
use App\User\Application\Deactivate\DeactivateUserCommandValidator;
use App\User\Domain\User;
use App\User\Infrastructure\Repository\UserRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Uid\Uuid;

class DeactivateUserCommandValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
    }

    #[Test]
    public function shouldThrowExceptionWhenUserIsNotFound(): void
    {
        $id = Uuid::v7();
        $this->userRepository->method('findById')->with($id)->willReturn(null);

        $handler = new DeactivateUserCommandValidator(
            $this->userRepository,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('U01: User was not found.');
        $handler(new DeactivateUserCommand($id));
    }

    #[Test]
    public function shouldThrowExceptionWhenUserIsAlreadyInactive(): void
    {
        $id = Uuid::v7();
        $user = new User();
        $user->setIsActive(false);

        $this->userRepository->method('findById')->with($id)->willReturn($user);

        $handler = new DeactivateUserCommandValidator(
            $this->userRepository,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('U02: User is inactive.');
        $handler(new DeactivateUserCommand($id));
    }
}

<?php

declare(strict_types=1);

namespace User\Application\Deactivate;

use App\User\Application\Deactivate\DeactivateUserCommand;
use App\User\Application\Deactivate\DeactivateUserCommandHandler;
use App\User\Application\Deactivate\DeactivateUserCommandValidator;
use App\User\Infrastructure\Repository\UserRepository;
use DomainException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
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

        $handler = new DeactivateUserCommandValidator($this->userRepository);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Użytkownik nie istnieje');
        $handler(new DeactivateUserCommand($id));

    }
}

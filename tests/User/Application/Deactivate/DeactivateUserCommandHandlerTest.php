<?php

declare(strict_types=1);

namespace User\Application\Deactivate;

use App\User\Application\Deactivate\DeactivateUserCommand;
use App\User\Application\Deactivate\DeactivateUserCommandHandler;
use App\User\Domain\User;
use App\User\Infrastructure\Repository\UserRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class DeactivateUserCommandHandlerTest extends TestCase
{
    #[Test]
    public function shouldChangeUserActivityStatus(): void
    {
        $id = Uuid::v7();
        $user = new User();
        $user->setIsActive(true)
            ->setEmail('test@example.com')
            ->setPassword('test');

        $repositoryMock = $this->createMock(UserRepository::class);
        $repositoryMock->method('findById')->with($id)->willReturn($user);

        $handler = new DeactivateUserCommandHandler($repositoryMock);

        $handler(new DeactivateUserCommand($id));

        $this->assertFalse($user->isActive());
    }
}

<?php

declare(strict_types=1);

namespace User\Application\ResetPassword;

use App\User\Application\ResetPassword\ResetPasswordCommand;
use App\User\Application\ResetPassword\ResetPasswordCommandHandler;
use App\User\Domain\Event\UserPasswordChangedEvent;
use App\User\Domain\User;
use App\User\Infrastructure\Repository\UserRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordCommandHandlerTest extends TestCase
{
    #[Test]
    public function shouldChangeUserPasswordAndDispatchEvent(): void
    {
        $email = 'test@example.com';
        $password = 'new-secure-password';

        $user = new User();
        $user->setEmail($email);

        $repositoryMock = $this->createMock(UserRepository::class);
        $repositoryMock->method('findByEmail')->with($email)->willReturn($user);
        $repositoryMock->expects($this->once())->method('save')->with($user);

        $hasherMock = $this->createMock(UserPasswordHasherInterface::class);
        $hasherMock->method('hashPassword')->with($user, $password)->willReturn('hashed-pwd');

        $dispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $dispatcherMock->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(UserPasswordChangedEvent::class));

        $handler = new ResetPasswordCommandHandler($repositoryMock, $hasherMock, $dispatcherMock);
        $handler(new ResetPasswordCommand($email, $password));

        $this->assertSame('hashed-pwd', $user->getPassword());
    }
}

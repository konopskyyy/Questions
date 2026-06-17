<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\User\Domain\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserPasswordChangedEvent extends Event
{
    public function __construct(
        public readonly User $user,
    ) {
    }
}

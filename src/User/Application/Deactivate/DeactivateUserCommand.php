<?php

declare(strict_types=1);

namespace App\User\Application\Deactivate;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
class DeactivateUserCommand
{
    public function __construct(
        public readonly Uuid $userId
    ) {
    }
}

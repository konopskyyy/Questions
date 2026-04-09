<?php

namespace App\Organization\Application\Query\GetOrganizationIdByUserId;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
class GetOrganizationIdByUserIdQuery
{
    public function __construct(
        public readonly Uuid $userId,
    ) {
    }
}

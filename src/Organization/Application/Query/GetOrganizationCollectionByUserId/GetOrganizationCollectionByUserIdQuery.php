<?php

namespace App\Organization\Application\Query\GetOrganizationCollectionByUserId;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
class GetOrganizationCollectionByUserIdQuery
{
    public function __construct(
        public readonly Uuid $userId,
    ) {
    }
}

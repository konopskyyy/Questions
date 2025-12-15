<?php

namespace App\Organization\Application\Query\GetOrganizationById;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
class GetOrganizationByIdQuery
{
    public function __construct(
        public Uuid $id,
    ) {
    }
}

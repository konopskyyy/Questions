<?php

namespace App\Organization\Application\Command\RemoveOrganization;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
class RemoveOrganizationCommand
{
    public function __construct(
        public Uuid $id,
    ) {
    }
}

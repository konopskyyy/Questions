<?php

namespace App\Organization\Application\DTO;

use App\Organization\Domain\Enum\OrganizationRole;
use Symfony\Component\Uid\Uuid;

class OrganizationMembershipDTO
{
    public function __construct(
        public Uuid $userId,
        public string $email,
        public OrganizationRole $role,
    ) {
    }

    public function __serialize(): array
    {
        return [
            'userId' => $this->userId,
            'email' => $this->email,
            'role' => $this->role->value,
        ];
    }
}

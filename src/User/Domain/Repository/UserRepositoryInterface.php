<?php

namespace App\User\Domain\Repository;

use App\User\Domain\User;
use Symfony\Component\Uid\Uuid;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function remove(User $user): void;

    public function findById(Uuid $id): ?User;

    public function findByEmail(string $email): ?User;

    public function getById(Uuid $id): User;

    public function isUserInAnotherOrganization(User $user): bool;
}

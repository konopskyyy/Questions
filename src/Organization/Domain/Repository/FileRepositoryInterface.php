<?php

namespace App\Organization\Domain\Repository;

use App\Organization\Domain\Entity\File;
use Symfony\Component\Uid\Uuid;

interface FileRepositoryInterface
{
    public function save(File $file): void;

    public function findById(Uuid $uuid): ?File;
}

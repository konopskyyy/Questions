<?php

namespace App\Organization\Infrastructure\Repository;

use App\Organization\Domain\Entity\File;
use App\Organization\Domain\Repository\FileRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Uuid;

class FileRepository implements FileRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(File::class);
    }

    public function save(File $file): void
    {
        $this->entityManager->persist($file);
        $this->entityManager->flush();
    }

    public function findById(Uuid $uuid): ?File
    {
        return $this->repository->find($uuid);
    }
}

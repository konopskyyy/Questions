<?php

namespace App\Organization\Application\Command\UploadOrganizationLogo;

use App\Organization\Domain\Entity\File;
use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\FileRepositoryInterface;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UploadOrganizationLogoHandler
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly LoggerInterface $logger,
        private readonly FileRepositoryInterface $fileRepository,
    ) {
    }

    public function __invoke(UploadOrganizationLogoCommand $command): void
    {
        $base64 = str_replace(' ', '+', $command->uploadOrganizationLogoDTO->file);

        $binary = base64_decode($base64, true);

        if ($binary === false) {
            throw new \RuntimeException('Base64 decode failed');
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'upload_');

        if ($tmpPath === false) {
            throw new \RuntimeException('Unable to create temporary file');
        }

        $result = file_put_contents($tmpPath, $binary);

        if ($result === false) {
            throw new \RuntimeException('Unable to write temporary file');
        }

        try {
            /** @var Organization $organization */
            $organization = $this->organizationRepository->find($command->organizationId);

            $file = new File();
            $file->setFilename(
                sprintf('%s_%s', $organization->getName(), $command->uploadOrganizationLogoDTO->id->toString())
            );
            $file->setMimeType($command->uploadOrganizationLogoDTO->mimeType);
            $file->setContentFromPath($tmpPath);

            $organization->setLogo($file);
            $this->organizationRepository->save($organization);
        } finally {
            if (file_exists($tmpPath)) {
                unlink($tmpPath);
            }
        }
    }

}

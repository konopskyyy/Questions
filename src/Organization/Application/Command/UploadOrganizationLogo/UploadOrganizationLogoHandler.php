<?php

namespace App\Organization\Application\Command\UploadOrganizationLogo;

use App\Organization\Domain\Entity\File;
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
        $base64 = $command->uploadOrganizationLogoDTO->file;

        $base64 = str_replace(" ", "+", $base64);
        $binary = base64_decode($base64, true);

        if ($binary === false) {
            $this->logger->info(
                message: '[UploadOrganizationLogoHandler] Base64 decode failed',
                context: [
                    'organization_id' => $command->organizationId,
                ],
            );
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'upload_');
        file_put_contents($tmpPath, $binary);

        try {
            $file = new File();
            $file->setFilename("TEST123");
            $file->setMimeType("image/png");
            $file->setContentFromPath($tmpPath);

            $organization = $this->organizationRepository->find($command->organizationId);
            $organization->setLogo($file);
            $this->organizationRepository->save($organization);
        } finally {
            if (file_exists($tmpPath)) {
                unlink($tmpPath);
            }
        }

    }
}

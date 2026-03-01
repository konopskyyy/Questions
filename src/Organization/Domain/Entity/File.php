<?php

namespace App\Organization\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class File
{
    #[ORM\Id]
    #[Column(type: 'uuid', length: 36, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[Column(type: 'string', length: 255)]
    private string $filename;

    #[Column(type: 'blob')]
    private $content;

    #[Column(type: 'string', length: 100)]
    private string $mimeType;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getContent(): string
    {
        if (!is_resource($this->content)) {
            return '';
        }

        rewind($this->content);

        return (string) stream_get_contents($this->content);
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setContentFromPath(string $path): self
    {
        $this->content = fopen($path, 'rb');

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[PrePersist]
    public function preCreate(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}

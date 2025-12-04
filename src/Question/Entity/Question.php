<?php

namespace App\Question\Entity;

use App\Question\Entity\Enum\QuestionStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'question')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'open' => OpenQuestion::class,
    'closed' => ClosedQuestion::class,
])]
abstract class Question
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\Column(length: 255)]
    private ?string $status = QuestionStatus::DRAFT->value;

    #[ORM\OneToMany(targetEntity: QuestionImage::class, mappedBy: 'question', cascade: ['persist', 'remove'])]
    private Collection $images;

    #[ORM\OneToOne(targetEntity: QuestionMetadata::class, inversedBy: 'question', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'metadata_id', referencedColumnName: 'id', nullable: true)]
    private ?QuestionMetadata $metadata = null;

    #[ORM\ManyToMany(targetEntity: QuestionTag::class, inversedBy: 'questions', cascade: ['persist'])]
    private Collection $tags;

    #[ORM\OneToMany(targetEntity: QuestionTip::class, mappedBy: 'question', cascade: ['persist'])]
    private Collection $tips;

    #[ORM\OneToMany(targetEntity: QuestionUrl::class, mappedBy: 'question', cascade: ['persist'])]
    private Collection $urls;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->tips = new ArrayCollection();
        $this->urls = new ArrayCollection();
        $this->status = QuestionStatus::DRAFT->value;
        $this->metadata = new QuestionMetadata();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setImages(Collection $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function setTags(Collection $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function setTips(Collection $tips): self
    {
        $this->tips = $tips;

        return $this;
    }

    public function setUrls(Collection $urls): self
    {
        $this->urls = $urls;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(QuestionStatus|string $status): static
    {
        $this->status = $status instanceof QuestionStatus ? $status->value : $status;

        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(QuestionImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setQuestion($this);
        }

        return $this;
    }

    public function removeImage(QuestionImage $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getQuestion() === $this) {
                $image->setQuestion(null);
            }
        }

        return $this;
    }

    public function getMetadata(): ?QuestionMetadata
    {
        return $this->metadata;
    }

    public function setMetadata(?QuestionMetadata $metadata): static
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(QuestionTag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(QuestionTag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getTips(): Collection
    {
        return $this->tips;
    }

    public function addTip(QuestionTip $tip): static
    {
        if (!$this->tips->contains($tip)) {
            $this->tips->add($tip);
            $tip->setQuestion($this);
        }

        return $this;
    }

    public function removeTip(QuestionTip $tip): static
    {
        if ($this->tips->removeElement($tip)) {
            if ($tip->getQuestion() === $this) {
                $tip->setQuestion(null);
            }
        }

        return $this;
    }

    public function getUrls(): Collection
    {
        return $this->urls;
    }

    public function addUrl(QuestionUrl $url): static
    {
        if (!$this->urls->contains($url)) {
            $this->urls->add($url);
            $url->setQuestion($this);
        }

        return $this;
    }

    public function removeUrl(QuestionUrl $url): static
    {
        if ($this->urls->removeElement($url)) {
            if ($url->getQuestion() === $this) {
                $url->setQuestion(null);
            }
        }

        return $this;
    }

    public function getType(): string
    {
        return match (get_class($this)) {
            OpenQuestion::class => 'open',
            ClosedQuestion::class => 'closed',
            default => 'unknown',
        };
    }
}

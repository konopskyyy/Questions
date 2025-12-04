<?php

namespace App\Organization\Domain\Entity;

use App\User\Domain\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Organization
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', length: 36, unique: true)]
    private Uuid $id;

    #[ORM\ManyToMany(targetEntity: User::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinTable(name: 'organization_recruiters')]
    #[JoinColumn(name: 'organization_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'recruiters_id', referencedColumnName: 'id')]
    private Collection $recruiters;

    #[ORM\ManyToMany(targetEntity: User::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinTable(name: 'organization_candidates')]
    #[JoinColumn(name: 'organization_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'candidates_id', referencedColumnName: 'id')]
    private Collection $candidates;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\Column(type: 'string', length: 255)]
        private string $name,

        #[ORM\Column(type: 'string', length: 255)]
        private string $logo,

        #[ORM\OneToOne(targetEntity: Address::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
        #[ORM\JoinColumn(name: 'address_id', referencedColumnName: 'id', nullable: true)]
        private Address $address,
    ) {
        $this->recruiters = new ArrayCollection();
        $this->candidates = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRecruiters(): Collection
    {
        return $this->recruiters;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function addRecruter(User $user): void
    {
        if ($user->isRecruter() && !$this->recruiters->contains($user)) {
            $this->recruiters->add($user);
        }
    }

    public function removeRecruter(User $user): void
    {
        $this->recruiters->removeElement($user);
    }

    #[PrePersist]
    public function preCreate(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}

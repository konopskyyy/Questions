<?php

namespace App\Organization\Domain\Entity;

use App\Organization\Domain\Enum\OrganizationRole;
use App\User\Domain\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(
    name: 'organization_membership',
    uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_organization_membership_user', columns: ['organization_id', 'user_id'])]
)]
#[ORM\HasLifecycleCallbacks]
class OrganizationMembership
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', length: 36, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'memberships')]
    #[ORM\JoinColumn(name: 'organization_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Organization $organization = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(type: 'string', length: 32, enumType: OrganizationRole::class)]
    private ?OrganizationRole $role = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct(?Organization $organization = null, ?User $user = null, ?OrganizationRole $role = null)
    {
        $this->organization = $organization;
        $this->user = $user;
        $this->role = $role;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOrganization(): Organization
    {
        if (!$this->organization) {
            throw new \LogicException('Organization membership has no organization assigned.');
        }

        return $this->organization;
    }

    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getUser(): User
    {
        if (!$this->user) {
            throw new \LogicException('Organization membership has no user assigned.');
        }

        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRole(): OrganizationRole
    {
        if (!$this->role) {
            throw new \LogicException('Organization membership has no role assigned.');
        }

        return $this->role;
    }

    public function setRole(OrganizationRole $role): self
    {
        $this->role = $role;

        return $this;
    }

    #[ORM\PrePersist]
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

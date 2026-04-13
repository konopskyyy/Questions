<?php

namespace App\Organization\Domain\Entity;

use App\Organization\Domain\Enum\OrganizationRole;
use App\User\Domain\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Organization
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', length: 36, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    /**
     * @var Collection<int, OrganizationMembership>
     */
    #[ORM\OneToMany(targetEntity: OrganizationMembership::class, mappedBy: 'organization', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $memberships;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(targetEntity: File::class, cascade: ['persist'], orphanRemoval: true)]
    #[ORM\JoinColumn(name: 'logo_id', referencedColumnName: 'id', nullable: true)]
    private ?File $logo = null;

    public function __construct(
        #[ORM\Column(type: 'string', length: 255)]
        private string $name,

        #[ORM\OneToOne(targetEntity: Address::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
        #[ORM\JoinColumn(name: 'address_id', referencedColumnName: 'id', nullable: true)]
        private Address $address,

        #[ORM\Column(type: 'string', length: 120)]
        private string $taxId,
    ) {
        $this->memberships = new ArrayCollection();
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

    /**
     * @return Collection<int, OrganizationMembership>
     */
    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMembership(OrganizationMembership $membership): void
    {
        if ($this->memberships->contains($membership)) {
            return;
        }

        $membership->setOrganization($this);

        $user = $membership->getUser();
        $role = $membership->getRole();

        if ($user) {
            $existingMembership = $this->findMembershipByUser($user);

            if ($existingMembership && $existingMembership !== $membership) {
                if ($role) {
                    $existingMembership->setRole($role);
                }

                return;
            }
        }

        $this->memberships->add($membership);
    }

    public function removeMembership(OrganizationMembership $membership): void
    {
        $this->memberships->removeElement($membership);
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getCandidates(): Collection
    {
        return $this->getUsersByRole(OrganizationRole::CANDIDATE);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogo(): ?File
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

    public function addCandidate(User $candidate): void
    {
        $this->addMember($candidate, OrganizationRole::CANDIDATE);
    }

    public function removeCandidate(User $candidate): void
    {
        $this->removeMember($candidate, OrganizationRole::CANDIDATE);
    }

    public function addRecruiter(User $user): void
    {
        $this->addMember($user, OrganizationRole::RECRUITER);
    }

    public function addOwner(User $user): void
    {
        $this->addMember($user, OrganizationRole::OWNER);
    }

    public function removeRecruiter(User $user): void
    {
        $this->removeMember($user, OrganizationRole::RECRUITER);
    }

    public function findRecruiter(User $user)
    {
        return $this->getRecruiters()->filter(fn (User $recruiter) => $recruiter->getId() === $user->getId());
    }

    public function hasMember(User $user): bool
    {
        return null !== $this->findMembershipByUser($user);
    }

    public function hasMemberWithRole(User $user, OrganizationRole $role): bool
    {
        return null !== $this->findMembershipByUserAndRole($user, $role);
    }

    public function addMember(User $user, OrganizationRole $role): void
    {
        $membership = $this->findMembershipByUser($user);

        if ($membership) {
            return;
        }

        $this->addMembership(new OrganizationMembership($this, $user, $role));
    }

    public function changeMemberRole(User $user, OrganizationRole $role): void
    {
        $membership = $this->findMembershipByUser($user);

        if (!$membership) {
            $this->addMembership(new OrganizationMembership($this, $user, $role));

            return;
        }

        $membership->setRole($role);
    }

    public function removeMember(User $user, ?OrganizationRole $role = null): void
    {
        $membership = null === $role
            ? $this->findMembershipByUser($user)
            : $this->findMembershipByUserAndRole($user, $role);

        if (!$membership) {
            return;
        }

        $this->memberships->removeElement($membership);
    }

    public function hasRole(OrganizationRole $role): bool
    {
        return $this->memberships->exists(
            static fn (int $key, OrganizationMembership $membership): bool => $membership->getRole() === $role
        );
    }

    public function getRecruiters(): Collection
    {
        return $this->getUsersByRole(OrganizationRole::RECRUITER);
    }

    private function getUsersByRole(OrganizationRole $role): Collection
    {
        return new ArrayCollection(
            $this->memberships
                ->filter(
                    static fn (OrganizationMembership $membership): bool => null !== $membership->getUser()
                        && $membership->getRole() === $role
                )
                ->map(function (OrganizationMembership $membership): User {
                    $user = $membership->getUser();

                    if (!$user) {
                        throw new \LogicException('Organization membership must have user assigned.');
                    }

                    return $user;
                })
                ->toArray(),
        );
    }

    private function findMembershipByUser(User $user): ?OrganizationMembership
    {
        $memberships = $this->memberships->filter(
            static fn (OrganizationMembership $membership): bool => $membership->getUser()?->getId() == $user->getId()
        );

        $membership = $memberships->first();

        return false === $membership ? null : $membership;
    }

    private function findMembershipByUserAndRole(User $user, OrganizationRole $role): ?OrganizationMembership
    {
        $memberships = $this->memberships->filter(
            static fn (OrganizationMembership $membership): bool => $membership->getUser()?->getId() == $user->getId()
                && $membership->getRole() === $role
        );

        $membership = $memberships->first();

        return false === $membership ? null : $membership;
    }

    public function getTaxId(): string
    {
        return $this->taxId;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setLogo(File $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function setTaxId(string $taxId): self
    {
        $this->taxId = $taxId;

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

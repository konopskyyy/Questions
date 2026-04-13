<?php

namespace App\Organization\Domain\Factory;

use App\Organization\Application\DTO\AddressDTO;
use App\Organization\Application\DTO\OrganizationDTO;
use App\Organization\Application\DTO\OrganizationMembershipDTO;
use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Entity\OrganizationMembership;
use App\Organization\Domain\Enum\OrganizationRole;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class OrganizationFactory
{
    public function __construct(
        private readonly AddressFactory $addressService,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function create(
        string $name,
        string $taxId,
        AddressDTO $addressDto,
        array $recruiters,
    ): Organization {
        $organization = new Organization(
            name: $name,
            address: $this->addressService->create(
                street: $addressDto->street,
                buildingNo: $addressDto->buildingNo,
                apartmentNo: $addressDto->apartmentNo,
                city: $addressDto->city,
                postalCode: $addressDto->postalCode,
                country: $addressDto->country,
            ),
            taxId: $taxId,
        );

        foreach ($recruiters as $recruiterId) {
            $recruiter = $this->userRepository->getById($recruiterId);
            $organization->addMember($recruiter, OrganizationRole::RECRUITER);
        }

        return $organization;
    }

    public function createDto(Organization $organization): OrganizationDTO
    {
        return $this->createDtoWithMembershipFilter($organization);
    }

    public function createDtoForUser(Organization $organization, Uuid $userId): OrganizationDTO
    {
        return $this->createDtoWithMembershipFilter($organization, $userId);
    }

    private function createDtoWithMembershipFilter(Organization $organization, ?Uuid $userId = null): OrganizationDTO
    {
        /** @var list<OrganizationMembershipDTO> $memberships */
        $memberships = $organization->getMemberships()
            ->filter(
                static fn (OrganizationMembership $membership): bool => null === $userId
                    || $membership->getUser()?->getId() == $userId
            )
            ->map(function (OrganizationMembership $membership): OrganizationMembershipDTO {
                $user = $membership->getUser();
                $role = $membership->getRole();

                if (!$user || !$role) {
                    throw new \LogicException('Organization membership must have user and role assigned.');
                }

                $membershipUserId = $user->getId();

                if (!$membershipUserId) {
                    throw new \LogicException('Organization membership user must have an id.');
                }

                return new OrganizationMembershipDTO(
                    userId: $membershipUserId,
                    email: $user->getEmail() ?? 'empty',
                    role: $role,
                );
            })
            ->toArray();

        return new OrganizationDTO(
            id: $organization->getId(),
            name: $organization->getName(),
            taxId: $organization->getTaxId(),
            address: $this->addressService->createDto(
                address: $organization->getAddress(),
            ),
            createdAt: $organization->getCreatedAt(),
            updatedAt: $organization->getUpdatedAt(),
            memberships: $memberships,
        );
    }
}

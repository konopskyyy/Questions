<?php

namespace App\Common\Listener;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Uid\Uuid;

#[AsEventListener(event: Events::AUTHENTICATION_SUCCESS)]
class JwtCreatedListener
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
    ) {
    }

    public function __invoke(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();

        /** @var User $user */
        $user = $event->getUser();

        /** @var Uuid $userId */
        $userId = $user->getId();

        if ($user->isRecruiter()) {
            /** @var Organization $organization */
            $organization = $this->organizationRepository->findByRecruiterId($userId);

            $data['organizationId'] = $organization->getId()->toString();
        } else {
            /** @var Organization $organization */
            $organization = $this->organizationRepository->findByCandidateId($userId);

            $data['organizationId'] = $organization->getId()->toString();
        }

        $event->setData($data);
    }
}

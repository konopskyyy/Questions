<?php

namespace App\Common\Listener;

use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Events::JWT_CREATED)]
final class JwtCreatedListener
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
    ) {
    }

    public function __invoke(JWTCreatedEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $payload = $event->getData();

        $organization = $user->isRecruiter()
            ? $this->organizationRepository->findByRecruiterId($user->getId())
            : $this->organizationRepository->findByCandidateId($user->getId());

        $payload['organizationId'] = $organization?->getId()->toRfc4122();

        $event->setData($payload);
    }
}

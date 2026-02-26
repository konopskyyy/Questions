<?php

namespace App\User\Application\Listener;

use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Domain\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Uid\Uuid;

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

        $payload = $event->getData();

        /** @var Uuid $userId */
        $userId = $user->getId();

        $organization = $this->organizationRepository->findByRecruiterId($userId);

        $payload['organizationId'] = $organization?->getId()->toRfc4122();
        $payload['userId'] = $userId->toRfc4122();

        $event->setData($payload);
    }
}

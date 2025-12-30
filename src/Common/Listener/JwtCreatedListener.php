<?php

namespace App\Common\Listener;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Events::AUTHENTICATION_SUCCESS)]
class JwtCreatedListener
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
    ) {
    }

    public function __invoke(AuthenticationSuccessEvent $event) {
        $data = $event->getData();
        $user = $event->getUser();

        if ($user->isRecruiter()) {
            /** @var Organization $organization */
            $organization = $this->organizationRepository->findByRecruiterId($user->getId());

            $data['organizationId'] = $organization->getId()->toString();
        } else {
            /** @var Organization $organization */
            $organization = $this->organizationRepository->findByCandidateId($user->getId());

            $data['organizationId'] = $organization->getId()->toString();
        }

        $event->setData($data);
    }
}

<?php

declare(strict_types=1);

namespace App\User\UserInterface\Controller;

use App\Organization\Application\Query\GetOrganizationIdByUserId\GetOrganizationIdByUserIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class UserController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $queryBus,
    ) {
    }

    #[Route('/api/user/{userId}/organization', name: 'app_api_user_organization', methods: ['GET'])]
    public function getOrganizationIdByUserId(string $userId): Response
    {
        if (!Uuid::isValid($userId)) {
            return new JsonResponse(data: 'Invalid UUID', status: Response::HTTP_BAD_REQUEST);
        }

        $envelope = $this->queryBus->dispatch(
            message: new GetOrganizationIdByUserIdQuery(
                userId: Uuid::fromString($userId),
            )
        );

        return new JsonResponse(
            data: [
                'organizationId' => $envelope->last(HandledStamp::class)?->getResult(),
            ],
            status: Response::HTTP_OK,
        );
    }
}

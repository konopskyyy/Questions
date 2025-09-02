<?php

declare(strict_types=1);

namespace App\User\UserInterface\Controller;

use App\User\Application\Deactivate\DeactivateUserCommand;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class DeactivateController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('/api/user/:id/deactivate', name: 'api_user_deactivate', methods: ['POST'])]
    public function deactivate(Uuid $id): JsonResponse
    {
        try {
            $this->messageBus->dispatch(new DeactivateUserCommand($id));

            return $this->json(['status' => 'User deactivated'], Response::HTTP_OK);
        } catch (\DomainException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return $this->json(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

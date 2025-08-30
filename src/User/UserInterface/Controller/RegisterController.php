<?php

declare(strict_types=1);

namespace App\User\UserInterface\Controller;

use App\User\Application\CreateUser\CreateUserCommand;
use App\User\UserInterface\Controller\Dto\UserRegisterDto;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/api/user', name: 'api_user_register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload] UserRegisterDto $dto,
    ): JsonResponse {
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['error' => (string) $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->messageBus->dispatch(new CreateUserCommand($dto->email, $dto->password));

            return $this->json(['status' => 'User created'], Response::HTTP_CREATED);
        } catch (\DomainException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return $this->json(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

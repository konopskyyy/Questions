<?php

namespace App\Question\Controller;

use App\Question\Application\CreateQuestion\CreateQuestionCommand;
use App\Question\Controller\Dto\QuestionCreateDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuestionCreateController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/api/questions', name: 'app_api_questions_create', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] QuestionCreateDto $dto,
    ): JsonResponse {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json(['error' => (string) $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $envelope = $this->commandBus->dispatch(new CreateQuestionCommand($dto));
            $handled = $envelope->last(HandledStamp::class);
            $uuid = $handled?->getResult();

            return $this->json(['id' => (string) $uuid], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

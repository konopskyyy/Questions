<?php

namespace App\Question\Controller;

use App\Question\Application\CreateQuestion\CreateQuestionCommand;
use App\Question\Dto\QuestionCreateDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuestionCreateController extends AbstractController
{
    #[Route('/api/questions', name: 'question_create', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] QuestionCreateDto $dto,
        ValidatorInterface $validator,
        MessageBusInterface $bus,
    ): JsonResponse {
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['error' => (string) $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $envelope = $bus->dispatch(new CreateQuestionCommand($dto));
            $handled = $envelope->last(\Symfony\Component\Messenger\Stamp\HandledStamp::class);
            $uuid = $handled?->getResult();

            return $this->json(['id' => (string) $uuid], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            // return $this->json(['error' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

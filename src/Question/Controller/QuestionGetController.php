<?php

namespace App\Question\Controller;

use App\Question\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class QuestionGetController extends AbstractController
{
    #[Route('/api/questions/{id}', name: 'question_get', methods: ['GET'])]
    public function __invoke(string $id, QuestionRepository $questionRepository): JsonResponse
    {
        $question = $questionRepository->find(Uuid::fromString($id));
        if (!$question) {
            return $this->json(['error' => 'Question not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(QuestionResponseMapper::map($question));
    }
}

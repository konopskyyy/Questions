<?php

declare(strict_types=1);

namespace App\Question\Application\AcceptQuestion;

use App\Question\Entity\Enum\QuestionStatus;
use App\Question\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AcceptQuestionCommandHandler
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function __invoke(AcceptQuestionCommand $command): void
    {
        $question = $this->questionRepository->find($command->questionId);

        if (!$question) {
            return;
        }
        $question->setStatus(QuestionStatus::ACCEPTED->value);
        $this->em->flush();
    }
}

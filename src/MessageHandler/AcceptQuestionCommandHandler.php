<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Enum\QuestionStatus;
use App\Message\AcceptQuestionCommand;
use App\Repository\QuestionRepository;
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

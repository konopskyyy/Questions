<?php

namespace App\Message;

use App\Enum\QuestionStatus;
use App\Messenger\Middleware\AsMessageValidator;
use App\Repository\QuestionRepository;

#[AsMessageValidator]
class AcceptQuestionCommandValidator
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
    ) {
    }

    public function validate(AcceptQuestionCommand $command): void
    {
        $question = $this->questionRepository->find($command->questionId);

        if ($question->getStatus() != QuestionStatus::DRAFT->value) {
            throw new \DomainException('Niepoprawny status');
        }
        if (empty($command->questionId)) {
            throw new \InvalidArgumentException('Brak ID pytania do akceptacji.');
        }
    }
}

<?php

namespace App\Question\Application\AcceptQuestion;

use App\Common\Middleware\AsMessageValidator;
use App\Question\Entity\Enum\QuestionStatus;
use App\Question\Repository\QuestionRepository;

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

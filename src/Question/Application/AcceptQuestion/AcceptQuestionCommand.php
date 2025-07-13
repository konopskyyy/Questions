<?php

namespace App\Question\Application\AcceptQuestion;

class AcceptQuestionCommand
{
    public function __construct(
        public readonly string $questionId,
    ) {
    }
}

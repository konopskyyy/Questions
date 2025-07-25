<?php

namespace App\Question\Application\CreateQuestion;

use App\Question\Dto\QuestionCreateDto;

class CreateQuestionCommand
{
    public function __construct(
        public readonly QuestionCreateDto $dto,
    ) {
    }
}

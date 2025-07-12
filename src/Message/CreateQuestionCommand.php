<?php

namespace App\Message;

use App\Dto\QuestionCreateDto;

class CreateQuestionCommand
{
    public function __construct(
        public readonly QuestionCreateDto $dto,
    ) {
    }
}

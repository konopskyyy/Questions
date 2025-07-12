<?php

namespace App\Message;

class AcceptQuestionCommand
{
    public function __construct(
        public readonly string $questionId,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Question\Entity\Enum;

enum QuestionType: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
}

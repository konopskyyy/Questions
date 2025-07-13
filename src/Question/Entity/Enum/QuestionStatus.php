<?php

namespace App\Question\Entity\Enum;

enum QuestionStatus: string
{
    case DRAFT = 'draft';
    case ACCEPTED = 'accepted';
    case FOR_VERIFICATION = 'for_verification';
}

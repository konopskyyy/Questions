<?php

namespace App\Enum;

enum QuestionStatus: string
{
    case DRAFT = 'draft';
    case ACCEPTED = 'accepted';
    case FOR_VERIFICATION = 'for_verification';
}

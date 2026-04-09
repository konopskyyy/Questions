<?php

namespace App\Organization\Domain\Enum;

enum OrganizationRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case RECRUITER = 'recruiter';
    case CANDIDATE = 'candidate';
}

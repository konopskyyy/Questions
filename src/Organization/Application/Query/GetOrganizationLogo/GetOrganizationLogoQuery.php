<?php

namespace App\Organization\Application\Query\GetOrganizationLogo;

use Symfony\Component\Uid\Uuid;

class GetOrganizationLogoQuery
{
    public function __construct(
        public Uuid $organizationId,
    ) {
    }
}

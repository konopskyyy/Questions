<?php

namespace App\Organization\Application\Exception;

class LogoNotFoundException extends \Exception
{
    public function __construct(string $organizationId)
    {
        parent::__construct(sprintf('Logo for organization with id %s not found', $organizationId));
    }
}

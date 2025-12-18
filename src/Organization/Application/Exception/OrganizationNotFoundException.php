<?php

namespace App\Organization\Application\Exception;

class OrganizationNotFoundException extends \Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct(message: $message);
    }
}

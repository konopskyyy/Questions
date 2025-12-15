<?php

namespace App\Organization\Application\Exception;

use JetBrains\PhpStorm\Pure;

class OrganizationNotFoundException extends \Exception
{
    #[Pure]
    public function __construct(string $message = "")
    {
        parent::__construct(message: $message);
    }

}

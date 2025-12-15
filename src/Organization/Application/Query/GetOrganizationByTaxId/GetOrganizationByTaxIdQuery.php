<?php

namespace App\Organization\Application\Query\GetOrganizationByTaxId;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Validator\Constraints as Assert;

#[AsMessage]
class GetOrganizationByTaxIdQuery
{
    public function __construct(
        #[Assert\NotBlank]
        public string $taxId,
    ) {
    }
}

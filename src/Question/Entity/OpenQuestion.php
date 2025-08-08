<?php

declare(strict_types=1);

namespace App\Question\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class OpenQuestion extends Question
{
    #[ORM\Column(type: Types::TEXT)]
    private ?string $answer = null;

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $body): self
    {
        $this->answer = $body;

        return $this;
    }
}

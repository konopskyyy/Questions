<?php

namespace App\Question\Entity;

use App\Repository\CorrectAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CorrectAnswerRepository::class)]
class CorrectAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $letter = null;

    #[ORM\ManyToOne(inversedBy: 'correctAnswers')]
    private ?ClosedQuestion $question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLetter(): ?string
    {
        return $this->letter;
    }

    public function setLetter(string $letter): static
    {
        $this->letter = $letter;

        return $this;
    }

    public function getQuestion(): ?ClosedQuestion
    {
        return $this->question;
    }

    public function setQuestion(?ClosedQuestion $question): static
    {
        $this->question = $question;

        return $this;
    }
}

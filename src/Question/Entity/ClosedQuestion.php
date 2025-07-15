<?php

declare(strict_types=1);

namespace App\Question\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ClosedQuestion extends Question
{
    /**
     * @var Collection<int, AnswerOption>
     */
    #[ORM\OneToMany(targetEntity: AnswerOption::class, mappedBy: 'question')]
    private Collection $answerOptions;

    /**
     * @var Collection<int, CorrectAnswer>
     */
    #[ORM\OneToMany(targetEntity: CorrectAnswer::class, mappedBy: 'question')]
    private Collection $correctAnswers;

    public function __construct()
    {
        parent::__construct();
        $this->answerOptions = new ArrayCollection();
        $this->correctAnswers = new ArrayCollection();
    }

    /**
     * @return Collection<int, AnswerOption>
     */
    public function getAnswerOptions(): Collection
    {
        return $this->answerOptions;
    }

    public function addAnswerOption(AnswerOption $answerOption): static
    {
        if (!$this->answerOptions->contains($answerOption)) {
            $this->answerOptions->add($answerOption);
            $answerOption->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswerOption(AnswerOption $answerOption): static
    {
        if ($this->answerOptions->removeElement($answerOption)) {
            // set the owning side to null (unless already changed)
            if ($answerOption->getQuestion() === $this) {
                $answerOption->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CorrectAnswer>
     */
    public function getCorrectAnswers(): Collection
    {
        return $this->correctAnswers;
    }

    public function addCorrectAnswer(CorrectAnswer $correctAnswer): static
    {
        if (!$this->correctAnswers->contains($correctAnswer)) {
            $this->correctAnswers->add($correctAnswer);
            $correctAnswer->setQuestion($this);
        }

        return $this;
    }

    public function removeCorrectAnswer(CorrectAnswer $correctAnswer): static
    {
        if ($this->correctAnswers->removeElement($correctAnswer)) {
            // set the owning side to null (unless already changed)
            if ($correctAnswer->getQuestion() === $this) {
                $correctAnswer->setQuestion(null);
            }
        }

        return $this;
    }
}

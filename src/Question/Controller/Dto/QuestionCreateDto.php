<?php

declare(strict_types=1);

namespace App\Question\Controller\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class QuestionCreateDto
{
    #[Assert\NotBlank]
    public string $body;

    public string $answer;

    #[Assert\NotBlank]
    public string $type;

    /**
     * @var array<int, array{name: string, url: string}>
     */
    public array $images = [];

    /**
     * @var array<int, array{name: string, description: string}>
     */
    public array $tags = [];

    /**
     * @var array<int, array{description: string}>
     */
    public array $tips = [];

    /**
     * @var array<int, array{description: string, url: string}>
     */
    public array $urls = [];

    /** @var array<int, array{letter: string, body: string, isCorrect: bool}> */
    public array $answerOptions = [];
}

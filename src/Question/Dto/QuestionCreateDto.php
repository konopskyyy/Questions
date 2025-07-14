<?php

namespace App\Question\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class QuestionCreateDto
{
    #[Assert\NotBlank]
    public string $body;

    #[Assert\NotBlank]
    public string $type;

    /**
     * @var array<int, array{name: string, url: string}>
     */
    public array $images = [];

    /**
     * @var array{createdBy?: string}|null
     */
    public ?array $metadata = null;

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
}

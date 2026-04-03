<?php

declare(strict_types=1);

namespace App\User\Domain\Validation;

class ValidationContext
{
    public function __construct(
        private readonly array $data = [],
    ) {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public static function empty(): self
    {
        return new self();
    }
}

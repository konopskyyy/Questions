<?php

declare(strict_types=1);

namespace App\User\Domain\Validation;

class ValidationContextFactory
{
    /**
     * @param class-string         $className
     * @param array<string, mixed> $data
     */
    public function create(string $className, ?string $email = null, ?string $id = null, array $data = []): ValidationContext
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        $shortName = (new \ReflectionClass($className))->getShortName();

        return new ValidationContext(array_merge([
            'shortName' => $shortName,
            'email' => $email,
            'id' => $id,
        ], $data));
    }
}

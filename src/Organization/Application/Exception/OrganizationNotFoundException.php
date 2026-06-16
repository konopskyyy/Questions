<?php

namespace App\Organization\Application\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class OrganizationNotFoundException extends \Exception implements HttpExceptionInterface
{
    public function __construct(string $message = '')
    {
        parent::__construct(message: $message ?: 'Organization not found');
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    public function getHeaders(): array
    {
        return [];
    }
}

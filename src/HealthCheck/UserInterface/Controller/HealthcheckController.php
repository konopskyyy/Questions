<?php

declare(strict_types=1);

namespace App\HealthCheck\UserInterface\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HealthcheckController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route(path: '/', name: 'app_main_healthcheck')]
    public function mainHealthCheck(): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_OK);
    }
}

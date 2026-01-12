<?php

namespace App\Organization\Presentation\Controller;

use App\Organization\Application\Command\AddCandidateToOrganization\AddCandidateToOrganizationCommand;
use App\Organization\Application\Command\AddCandidateToOrganization\DTO\AddCandidateToOrganizationDTO;
use App\Organization\Application\Command\AddRecruiterToOrganization\AddRecruiterToOrganizationCommand;
use App\Organization\Application\Command\AddRecruiterToOrganization\DTO\AddRecruiterToOrganizationDTO;
use App\Organization\Application\Command\CreateOrganization\CreateOrganizationCommand;
use App\Organization\Application\Command\CreateOrganization\DTO\CreateOrganizationDTO;
use App\Organization\Application\Command\RemoveOrganization\RemoveOrganizationCommand;
use App\Organization\Application\Command\UpdateOrganization\DTO\UpdateOrganizationDTO;
use App\Organization\Application\Command\UpdateOrganization\UpdateOrganizationCommand;
use App\Organization\Application\Query\GetOrganizationById\GetOrganizationByIdQuery;
use App\Organization\Application\Query\GetOrganizationByTaxId\GetOrganizationByTaxIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class OrganizationController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly MessageBusInterface $queryBus,
    ) {
    }

    #[Route(path: '/api/organization', name: 'app_api_organization_create', methods: [Request::METHOD_POST])]
    public function createAction(#[MapRequestPayload] CreateOrganizationDTO $createOrganizationDTO): JsonResponse
    {
        try {
            $this->commandBus->dispatch(
                message: new CreateOrganizationCommand(
                    createOrganizationDTO: $createOrganizationDTO,
                ),
            );

            $envelope = $this->queryBus->dispatch(
                message: new GetOrganizationByTaxIdQuery(
                    taxId: $createOrganizationDTO->taxId,
                )
            );

            $organizationDto = $envelope->last(HandledStamp::class)?->getResult();
        } catch (\Exception|ExceptionInterface $exception) {
            return new JsonResponse(
                data: $exception->getMessage(),
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        return $this->json(
            data: $organizationDto,
            status: Response::HTTP_OK,
        );
    }

    #[Route(path: '/api/organization/{id}', name: 'app_api_organization_update', methods: [Request::METHOD_PATCH])]
    public function updateOrganizationAction(
        string $id,
        #[MapRequestPayload] UpdateOrganizationDTO $updateOrganizationDTO,
    ): JsonResponse {
        if (!Uuid::isValid($id)) {
            return new JsonResponse(
                data: 'Invalid id',
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        try {
            $id = Uuid::fromString($id);

            $this->commandBus->dispatch(
                message: new UpdateOrganizationCommand(
                    id: $id,
                    updateOrganizationDTO: $updateOrganizationDTO,
                ),
            );

            $envelope = $this->queryBus->dispatch(
                message: new GetOrganizationByIdQuery(
                    id: $id,
                )
            );

            $organizationDto = $envelope->last(HandledStamp::class)?->getResult();
        } catch (\Exception|ExceptionInterface $exception) {
            return new JsonResponse(
                data: $exception->getMessage(),
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        return $this->json(
            data: $organizationDto,
            status: Response::HTTP_OK,
        );
    }

    #[Route(path: '/api/organization/{id}', name: 'app_api_organization_get', methods: [Request::METHOD_GET])]
    public function getOrganizationAction(string $id): JsonResponse
    {
        if (!Uuid::isValid($id)) {
            return new JsonResponse(
                data: 'Invalid id',
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        try {
            $envelope = $this->queryBus->dispatch(
                message: new GetOrganizationByIdQuery(
                    id: Uuid::fromString($id),
                )
            );

            $organizationDto = $envelope->last(HandledStamp::class)?->getResult();
        } catch (\Exception|ExceptionInterface $exception) {
            return new JsonResponse(
                data: $exception->getMessage(),
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        return $this->json(
            data: $organizationDto,
            status: Response::HTTP_OK,
        );
    }

    #[Route(path: '/api/organization/{id}', name: 'app_api_organization_remove', methods: [Request::METHOD_DELETE])]
    public function removeAction(string $id): JsonResponse
    {
        if (!Uuid::isValid($id)) {
            return new JsonResponse(
                data: 'Invalid id',
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        try {
            $this->commandBus->dispatch(
                message: new RemoveOrganizationCommand(
                    id: Uuid::fromString($id),
                ),
            );
        } catch (\Exception|ExceptionInterface $exception) {
            return new JsonResponse(
                data: $exception->getMessage(),
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }

    #[Route(
        path: '/api/organization/{organizationId}/recruiter/{recruiterId}',
        name: 'app_api_organization_add_recruiter',
        methods: [Request::METHOD_POST],
    )]
    public function addRecruiterToOrganizationAction(string $organizationId, string $recruiterId): JsonResponse
    {
        if (!Uuid::isValid($organizationId) || !Uuid::isValid($recruiterId)) {
            return new JsonResponse(
                data: 'Invalid id',
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        $this->commandBus->dispatch(
            message: new AddRecruiterToOrganizationCommand(
                addRecruiterToOrganizationDTO: new AddRecruiterToOrganizationDTO(
                    recruiterId: Uuid::fromString($recruiterId),
                    organizationId: Uuid::fromString($organizationId),
                ),
            )
        );

        return new JsonResponse(
            status: Response::HTTP_OK,
        );
    }

    #[Route(
        path: '/api/organization/{organizationId}/candidate/{candidateId}',
        name: 'app_api_organization_add_candidate',
        methods: [Request::METHOD_POST],
    )]
    public function addCandidateToOrganizationAction(string $organizationId, string $candidateId): JsonResponse
    {
        if (!Uuid::isValid($organizationId) || !Uuid::isValid($candidateId)) {
            return new JsonResponse(
                data: 'Invalid id',
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        $this->commandBus->dispatch(
            message: new AddCandidateToOrganizationCommand(
                addCandidateToOrganizationDTO: new AddCandidateToOrganizationDTO(
                    candidateId: Uuid::fromString($candidateId),
                    organizationId: Uuid::fromString($organizationId),
                ),
            )
        );

        return new JsonResponse(
            status: Response::HTTP_OK,
        );
    }
}

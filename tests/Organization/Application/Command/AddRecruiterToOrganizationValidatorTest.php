<?php

declare(strict_types=1);

namespace App\Tests\Organization\Application\Command;

use App\Common\Exception\ValidationFail;
use App\Organization\Application\Command\AddRecruiterToOrganization\AddRecruiterToOrganizationCommand;
use App\Organization\Application\Command\AddRecruiterToOrganization\AddRecruiterToOrganizationValidator;
use App\Organization\Application\Command\AddRecruiterToOrganization\DTO\AddRecruiterToOrganizationDTO;
use App\Organization\Domain\Entity\Address;
use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\Organization\Infrastructure\Repository\OrganizationRepository;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\User;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Uid\Uuid;

class AddRecruiterToOrganizationValidatorTest extends TestCase
{

    use ProphecyTrait;

    private OrganizationRepositoryInterface|ObjectProphecy $organizationRepository;
    private ObjectProphecy|UserRepositoryInterface $userRepository;
    private NullLogger $logger;

    public function setUp(): void
    {
        $this->organizationRepository = $this->prophesize(OrganizationRepositoryInterface::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->logger = new NullLogger();
    }

    public function testThrowRecruiterIsAssociatedWithThisOrganization(): void
    {
        $recruiterId = Uuid::v7();
        $organizationId = Uuid::v7();

        $dto = new AddRecruiterToOrganizationDto(
            recruiterId: $recruiterId,
            organizationId: $organizationId
        );
        $command = new AddRecruiterToOrganizationCommand($dto);

        $organization = new Organization(
            name: 'test organization',
            logo: "www.logo.pl",
            address: new Address('street', '1', '1', 'city', '11-430', 'pl'),
            taxId: '1231234531'
        );

        $recruiter = new User();
        $organization->addRecruiter($recruiter);

        $this->organizationRepository->find($organizationId)->willReturn($organization);
        $validator = new AddRecruiterToOrganizationValidator(
            $this->organizationRepository->reveal(),
            $this->userRepository->reveal(),
            $this->logger
        );

        $this->expectException(ValidationFail::class);
        $validator($command);
    }

    public function testDoesntThrowRecruiterIsAssociatedWithThisOrganizationWhenUserHasNotTheSameId(): void
    {
        $recruiterId = Uuid::v7();
        $organizationId = Uuid::v7();

        $dto = new AddRecruiterToOrganizationDto(
            recruiterId: $recruiterId,
            organizationId: $organizationId
        );
        $command = new AddRecruiterToOrganizationCommand($dto);

        $organization = new Organization(
            name: 'test organization',
            logo: "www.logo.pl",
            address: new Address('street', '1', '1', 'city', '11-430', 'pl'),
            taxId: '1231234531'
        );

        $recruiterFounded = new User();
        $recruiterFounded->setId(Uuid::v7());

        $recruiterFromCommand = new User();
        $recruiterFromCommand->setId($recruiterId);

        $organization->addRecruiter($recruiterFounded);

        $this->organizationRepository->find($organizationId)->willReturn($organization);
        $this->userRepository->findById($recruiterId)->willReturn($recruiterFromCommand);

        $validator = new AddRecruiterToOrganizationValidator(
            $this->organizationRepository->reveal(),
            $this->userRepository->reveal(),
            $this->logger
        );

        $this->expectNotToPerformAssertions();
        $validator($command);
    }
}

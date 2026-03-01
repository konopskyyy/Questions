<?php

declare(strict_types=1);

namespace App\Tests\User\Application\Listner;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\User\Application\Listener\JwtCreatedListener;
use App\User\Domain\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Uid\Uuid;

class JwtCreatedListenerTest extends TestCase
{
    use ProphecyTrait;

    private OrganizationRepositoryInterface|ObjectProphecy $organizationRepository;
    private User|ObjectProphecy $userMock;
    private ObjectProphecy|Organization $organizationMock;

    public function setUp(): void
    {
        $this->organizationRepository = $this->prophesize(OrganizationRepositoryInterface::class);
        $this->userMock = $this->prophesize(User::class);
        $this->organizationMock = $this->prophesize(Organization::class);
    }

    public function testJwtCreatedListenerReturnAdditionalData(): void
    {
        $payload = [];
        $userId = Uuid::v7();
        $organizationId = Uuid::v7();

        $this->userMock->getId()->willReturn($userId);
        $this->organizationRepository
            ->findByRecruiterId($userId)
            ->willReturn($this->organizationMock);
        $this->organizationMock->getId()->willReturn($organizationId);

        $listener = new JwtCreatedListener($this->organizationRepository->reveal());

        $event = new JWTCreatedEvent($payload, $this->userMock->reveal());
        $listener($event);

        $data = $event->getData();

        $this->assertSame((string) $userId, $data['userId']);
        $this->assertSame((string) $organizationId, $data['organizationId']);
    }
}

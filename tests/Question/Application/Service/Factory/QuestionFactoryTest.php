<?php

declare(strict_types=1);

use App\Question\Application\Service\Factory\QuestionFactory;
use App\Question\Dto\QuestionCreateDto;
use App\Question\Entity\QuestionTag;
use App\Question\Repository\QuestionTagRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class QuestionFactoryTest extends TestCase
{
    private QuestionFactory $factory;
    private QuestionTagRepository $questionTagRepository;

    protected function setUp(): void
    {
        $this->questionTagRepository = $this->createMock(QuestionTagRepository::class);
        $this->factory = new QuestionFactory(
            $this->questionTagRepository,
            new NullLogger()
        );
    }

    #[Test]
    public function shouldThrowExceptionWhenTypeIsIncorrect(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid question type provided.');

        $dto = new QuestionCreateDto();
        $dto->type = 'invalid_type';

        $this->factory->createFromDto($dto);
    }

    #[Test]
    public function shouldReturnMinimalOpenQuestion(): void
    {
        $dto = new QuestionCreateDto();
        $dto->type = 'open';
        $dto->body = 'body';

        $question = $this->factory->createFromDto($dto);

        $this->assertEquals(
            $dto->body,
            $question->getBody(),
            'Question body should match the DTO body.'
        );
    }

    #[Test]
    public function shouldReturnQuestionWithFoundedTags(): void
    {
        $dto = new QuestionCreateDto();
        $dto->type = 'open';
        $dto->body = 'body';
        $dto->tags = ['docker'];

        // Przygotuj mock zwracający tag dla 'docker'
        $tag = new QuestionTag();
        $tag->setName('docker')->setDescription('Docker');

        $this->questionTagRepository
            ->method('findOneBy')
            ->with(['name' => 'docker'])
            ->willReturn($tag);

        $question = $this->factory->createFromDto($dto);

        $this->assertCount(
            1,
            $question->getTags(),
            'Question should have one tag.'
        );
    }
}

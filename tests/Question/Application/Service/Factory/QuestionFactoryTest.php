<?php

declare(strict_types=1);

use App\Question\Application\Service\Factory\QuestionFactory;
use App\Question\Dto\QuestionCreateDto;
use App\Question\Entity\ClosedQuestion;
use App\Question\Entity\OpenQuestion;
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
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid question type provided.');

        $dto = new QuestionCreateDto();
        $dto->type = 'invalid_type';

        $this->factory->createFromDto($dto);
    }

    #[Test]
    public function shouldReturnOpenedQuestionWithAllData(): void
    {
        $tag = (new QuestionTag())
            ->setName('docker')
            ->setDescription('Docker');

        $dto = new QuestionCreateDto();
        $dto->type = 'open';
        $dto->body = 'body';
        $dto->tags = ['docker'];
        $dto->answer = 'test answer';
        $dto->images = [
            [
                'name' => 'docker_image',
                'url' => 'http://example.com/docker_image.jpg',
            ],
        ];
        $dto->tips = [
            ['description' => 'Tip 1'],
            ['description' => 'Tip 2'],
            ['description' => 'Tip 3'],
        ];
        $dto->urls = [
            ['description' => 'Docker Docs', 'url' => 'https://docs.docker.com/'],
            ['description' => 'Docker Hub', 'url' => 'https://hub.docker.com/'],
        ];

        $this->questionTagRepository
            ->method('findOneBy')
            ->with(['name' => 'docker'])
            ->willReturn($tag);

        $question = $this->factory->createFromDto($dto);

        $this->assertCount(1, $question->getTags(), 'Question should have one tag.');
        $this->assertCount(1, $question->getImages());
        $this->assertCount(3, $question->getTips());
        $this->assertCount(2, $question->getUrls());
        $this->assertEquals('test answer', $question->getAnswer());
        $this->assertInstanceOf(OpenQuestion::class, $question);
    }

    #[Test]
    public function shouldReturnClosedQuestionWithAllData(): void
    {
        $tag = (new QuestionTag())
            ->setName('docker')
            ->setDescription('Docker');

        $dto = new QuestionCreateDto();
        $dto->type = 'closed';
        $dto->body = 'body';
        $dto->tags = ['docker'];
        $dto->images = [
            [
                'name' => 'docker_image',
                'url' => 'http://example.com/docker_image.jpg',
            ],
        ];
        $dto->tips = [
            ['description' => 'Tip 1'],
            ['description' => 'Tip 2'],
            ['description' => 'Tip 3'],
        ];
        $dto->urls = [
            ['description' => 'Docker Docs', 'url' => 'https://docs.docker.com/'],
            ['description' => 'Docker Hub', 'url' => 'https://hub.docker.com/'],
        ];
        $dto->answerOptions = [
            ['letter' => 'A', 'body' => 'Option A'],
            ['letter' => 'B', 'body' => 'Option B'],
            ['letter' => 'C', 'body' => 'Option C'],
        ];
        $dto->correctAnswers = ['A', 'B'];

        $this->questionTagRepository
            ->method('findOneBy')
            ->with(['name' => 'docker'])
            ->willReturn($tag);

        $question = $this->factory->createFromDto($dto);

        $this->assertCount(1, $question->getTags(), 'Question should have one tag.');
        $this->assertCount(1, $question->getImages());
        $this->assertCount(3, $question->getTips());
        $this->assertCount(2, $question->getUrls());
        $this->assertCount(3, $question->getAnswerOptions());
        $this->assertCount(2, $question->getCorrectAnswers());
        $this->assertInstanceOf(ClosedQuestion::class, $question);
    }
}

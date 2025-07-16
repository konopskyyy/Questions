<?php

namespace App\Question\Application\Service\Factory;

use App\Question\Dto\QuestionCreateDto;
use App\Question\Entity\AnswerOption;
use App\Question\Entity\ClosedQuestion;
use App\Question\Entity\OpenQuestion;
use App\Question\Entity\Question;
use App\Question\Entity\QuestionImage;
use App\Question\Entity\QuestionMetadata;
use App\Question\Entity\QuestionTip;
use App\Question\Entity\QuestionUrl;
use App\Question\Repository\QuestionTagRepository;
use Psr\Log\LoggerInterface;

class QuestionFactory
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private readonly QuestionTagRepository $questionTagRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function createFromDto(QuestionCreateDto $dto): ClosedQuestion|OpenQuestion
    {
        $this->logger->info('Rozpoczynam tworzenie pytania z DTO', [
            'dto' => $dto,
        ]);

        $question = match ($dto->type) {
            'open' => $this->prepareOpenQuestion($dto),
            'closed' => $this->prepareClosedQuestion($dto),
            default => throw new \InvalidArgumentException('Invalid question type provided.'),
        };

        $this->logger->info('Dodane pytania z DTO', [
            'question' => $question,
        ]);

        return $question;
    }

    private function prepareOpenQuestion(QuestionCreateDto $dto): OpenQuestion
    {
        $question = new OpenQuestion();
        /** @var OpenQuestion $question */
        $question = $this->prepareGeneralDataOnQuestion($question, $dto);
        $question->setAnswer($dto->answer);

        return $question;
    }

    private function prepareClosedQuestion(QuestionCreateDto $dto): ClosedQuestion
    {
        $question = new ClosedQuestion();
        /** @var ClosedQuestion $question */
        $question = $this->prepareGeneralDataOnQuestion($question, $dto);

        foreach ($dto->answerOptions as $answerOption) {
            $question->addAnswerOption(
                (new AnswerOption())
                    ->setLetter($answerOption['letter'])
                    ->setBody($answerOption['body'])
                    ->setIsCorrect($answerOption['isCorrect'])
            );
        }

        return $question;
    }

    private function prepareGeneralDataOnQuestion(Question $question, QuestionCreateDto $dto): Question
    {
        $question->setBody($dto->body);

        $metadata = new QuestionMetadata();
        $metadata->setCreatedAt(new \DateTimeImmutable('NOW'));
        $metadata->setCreatedBy('Anonymous'); // todo to kiedys do zmiany
        $question->setMetadata($metadata);

        foreach ($dto->tags as $tagData) {
            $tag = $this->questionTagRepository->findOneBy(['name' => $tagData]);
            $this->logger->info('Odnalazlem tag', [
                'tag' => $tag,
                'name' => $tagData,
            ]);

            if (null !== $tag) {
                $question->addTag($tag);
            }
        }

        foreach ($dto->images as $img) {
            $image = new QuestionImage();
            $image->setName($img['name']);
            $image->setUrl($img['url']);
            $question->getImages()->add($image);
            $image->setQuestion($question);
        }

        foreach ($dto->tips as $tipData) {
            $tip = new QuestionTip();
            $tip->setDescription($tipData['description']);
            $question->getTips()->add($tip);
            $tip->setQuestion($question);
        }

        foreach ($dto->urls as $urlData) {
            $url = new QuestionUrl();
            $url->setDescription($urlData['description']);
            $url->setUrl($urlData['url']);
            $question->getUrls()->add($url);
            $url->setQuestion($question);
        }

        return $question;
    }
}

<?php

namespace App\Question\Application\Service\Factory;

use App\Question\Dto\QuestionCreateDto;
use App\Question\Entity\Question;
use App\Question\Entity\QuestionImage;
use App\Question\Entity\QuestionMetadata;
use App\Question\Entity\QuestionTag;
use App\Question\Entity\QuestionTip;
use App\Question\Entity\QuestionUrl;
use App\Question\Repository\QuestionTagRepository;
use Psr\Log\LoggerInterface;

class QuestionFactory
{
    public function __construct(
        private readonly QuestionTagRepository $questionTagRepository,
        private readonly LoggerInterface $logger
    )
    {

    }
    public function createFromDto(QuestionCreateDto $dto): Question
    {
        $this->logger->info('Rozpoczynam tworzenie pytania z DTO', [
            'dto' => $dto,
        ]);

        if ($dto->type !== 'open') {
            throw new \InvalidArgumentException('Invalid question type provided.');
        }

        $question = new Question();
        $question->setBody($dto->body);
        $question->setType($dto->type);

        $metadata = new QuestionMetadata();
        $metadata->setCreatedAt(new \DateTimeImmutable('NOW'));
        $metadata->setCreatedBy('Anontymous');
        $question->setMetadata($metadata);

        foreach ($dto->tags as $tagData) {
            $tag = $this->questionTagRepository->findOneBy(['name' => $tagData]);
            $this->logger->info('Odnalazlem tag', [
                'tag' => $tag,
                'name' => $tagData,
            ]);

            if ($tag !== null) {
                $question->addTag($tag);
            }
        }

        $this->logger->info('Dodane pytania z DTO', [
            'question' => $question,
        ]);

//        foreach ($dto->images as $img) {
//            $image = new QuestionImage();
//            $image->setName($img['name'] ?? '');
//            $image->setUrl($img['url'] ?? '');
//            $question->getImages()->add($image);
//            $image->setQuestion($question);
//        }
//
//        if ($dto->metadata) {
//            $metadata = new QuestionMetadata();
//            // todo czas do poprawy  - strefa czasowa?
//            $metadata->setCreatedAt(new \DateTimeImmutable('NOW'));
//            $metadata->setCreatedBy($dto->metadata['createdBy'] ?? null);
//            $question->setMetadata($metadata);
//        }
//
//
//
//        foreach ($dto->tips as $tipData) {
//            $tip = new QuestionTip();
//            $tip->setDescription($tipData['description'] ?? '');
//            $question->getTips()->add($tip);
//            $tip->setQuestion($question);
//        }
//
//        foreach ($dto->urls as $urlData) {
//            $url = new QuestionUrl();
//            $url->setDescription($urlData['description'] ?? '');
//            $url->setUrl($urlData['url'] ?? '');
//            $question->getUrls()->add($url);
//            $url->setQuestion($question);
//        }

        return $question;
    }
}

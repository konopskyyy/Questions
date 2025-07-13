<?php

namespace App\Question\Application\Service\Factory;

use App\Question\Dto\QuestionCreateDto;
use App\Question\Entity\Question;
use App\Question\Entity\QuestionImage;
use App\Question\Entity\QuestionMetadata;
use App\Question\Entity\QuestionTag;
use App\Question\Entity\QuestionTip;
use App\Question\Entity\QuestionUrl;

class QuestionFactory
{
    public static function createFromDto(QuestionCreateDto $dto): Question
    {
        $question = new Question();
        $question->setBody($dto->body);
        $question->setType($dto->type);

        foreach ($dto->images as $img) {
            $image = new QuestionImage();
            $image->setName($img['name'] ?? '');
            $image->setUrl($img['url'] ?? '');
            $question->getImages()->add($image);
            $image->setQuestion($question);
        }

        if ($dto->metadata) {
            $metadata = new QuestionMetadata();
            // todo czas do poprawy  - strefa czasowa?
            $metadata->setCreatedAt(new \DateTimeImmutable('NOW'));
            $metadata->setCreatedBy($dto->metadata['createdBy'] ?? null);
            $question->setMetadata($metadata);
        }

        foreach ($dto->tags as $tagData) {
            $tag = new QuestionTag();
            $tag->setName($tagData['name'] ?? '');
            $tag->setDescription($tagData['description'] ?? '');
            $question->getTags()->add($tag);
        }

        foreach ($dto->tips as $tipData) {
            $tip = new QuestionTip();
            $tip->setDescription($tipData['description'] ?? '');
            $question->getTips()->add($tip);
            $tip->setQuestion($question);
        }

        foreach ($dto->urls as $urlData) {
            $url = new QuestionUrl();
            $url->setDescription($urlData['description'] ?? '');
            $url->setUrl($urlData['url'] ?? '');
            $question->getUrls()->add($url);
            $url->setQuestion($question);
        }

        return $question;
    }
}

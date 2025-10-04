<?php

namespace App\Question\Controller;

use App\Question\Entity\Question;

class QuestionResponseMapper
{
    public static function map(Question $question): array
    {
        return [
            'id' => (string) $question->getId(),
            'body' => $question->getBody(),
            'type' => $question->getType(),
            'status' => $question->getStatus(),
            'images' => array_map(fn ($img) => [
                'name' => $img->getName(),
                'url' => $img->getUrl(),
            ], $question->getImages()->toArray()),
            'tags' => array_map(fn ($tag) => [
                'name' => $tag->getName(),
                'description' => $tag->getDescription(),
            ], $question->getTags()->toArray()),
            'metadata' => $question->getMetadata() ? [
                'createdAt' => $question->getMetadata()->getCreatedAt() ?
                    $question->getMetadata()->getCreatedAt()->format(DATE_ATOM) : null,
                'createdBy' => $question->getMetadata()->getCreatedBy() ?
                    $question->getMetadata()->getCreatedBy() : null,
            ] : null,
            'tips' => array_map(fn ($tip) => [
                'description' => $tip->getDescription(),
            ], $question->getTips()->toArray()),
            'urls' => array_map(fn ($url) => [
                'id' => $url->getId(),
                'url' => $url->getUrl(),
            ], $question->getUrls()->toArray()),
        ];
    }
}

<?php

namespace App\Question\Application\CreateQuestion;

use App\Question\Application\Service\Factory\QuestionFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
class CreateQuestionCommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly QuestionFactory $questionFactory,
    ) {
    }

    public function __invoke(CreateQuestionCommand $command): Uuid
    {
        $question = $this->questionFactory->createFromDto($command->dto);
        $this->em->persist($question);
        $this->em->flush();
        /** @var Uuid $id */
        $id = $question->getId();

        return $id;
    }
}

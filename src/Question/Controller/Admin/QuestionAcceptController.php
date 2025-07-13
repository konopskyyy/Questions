<?php

namespace App\Question\Controller\Admin;

use App\Question\Application\AcceptQuestion\AcceptQuestionCommand;
use App\Question\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class QuestionAcceptController extends AbstractController
{
    #[Route('/admin/question/{id}/accept', name: 'admin_app_question_accept')]
    public function __invoke(
        string $id,
        EntityManagerInterface $em,
        MessageBusInterface $bus,
    ) {
        $question = $em->getRepository(Question::class)->find($id);
        if (!$question) {
            $this->addFlash('sonata_flash_error', 'Pytanie nie istnieje.');

            return $this->redirectToRoute('admin_app_question_list');
        }
        // Wywołanie komendy przez Messenger
        $bus->dispatch(new AcceptQuestionCommand($id));
        $this->addFlash('sonata_flash_success', 'Pytanie zostało zaakceptowane.');

        return $this->redirectToRoute('question_show', ['id' => $id]);
    }
}

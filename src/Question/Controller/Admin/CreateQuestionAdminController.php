<?php

namespace App\Question\Controller\Admin;

use App\Question\Entity\OpenQuestion;
use App\Question\Entity\QuestionMetadata;
use App\Question\Entity\QuestionTag;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;

class CreateQuestionAdminController extends CRUDController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {

    }
    public function openQuestionCreateAction(Request $request)
    {
        // Tworzymy nową instancję pytania otwartego
        $question = new OpenQuestion();

        // Tworzymy formularz ręcznie, na podstawie konfiguracji configureFormFields
        $form = $this->createFormBuilder($question)
            ->add('body')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Open' => 'open',
                    'Closed' => 'closed',
                ],
                'label' => 'Type',
            ])
            ->add('images', CollectionType::class, [
                'by_reference' => false,
                'required' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'admin_code' => 'admin.question_image',
            ])
            ->add('tags', EntityType::class, [
                'class' => QuestionTag::class,
                'multiple' => true,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Tags',
            ])
            ->add('tips', CollectionType::class, [
                'by_reference' => false,
                'required' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'admin_code' => 'admin.question_tip',
            ])
            ->add('urls', CollectionType::class, [
                'by_reference' => false,
                'required' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'admin_code' => 'admin.question_url',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManagerInterface $em */
            $em = $this->entityManager;

            $em->persist($question);
            $em->flush();

            // Przekierowanie po zapisie, np. do listy pytań
            return $this->redirectToRoute('question_list'); // dostosuj nazwę trasy
        }

        // Renderowanie formularza w szablonie
        return $this->render('@Admin/question/open_question_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

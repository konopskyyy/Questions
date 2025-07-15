<?php

namespace App\Question\Controller\Admin;

use App\Question\Admin\Type\QuestionImageType;
use App\Question\Admin\Type\QuestionTipType;
use App\Question\Admin\Type\QuestionUrlType;
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
        $question = new OpenQuestion();

        $form = $this->createFormBuilder($question)
            ->add('body')
            ->add('answer')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Open' => 'open',
                    'Closed' => 'closed',
                ],
                'label' => 'Type',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => QuestionImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'label' => 'Images',
                'prototype' => true,
            ])
            ->add('tags', EntityType::class, [
                'class' => QuestionTag::class,
                'multiple' => true,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Tags',
            ])
            ->add('tips', CollectionType::class, [
                'entry_type' => QuestionTipType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'label' => 'Tips',
                'prototype' => true,
            ])
            ->add('urls', CollectionType::class, [
                'entry_type' => QuestionUrlType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'label' => 'URLs',
                'prototype' => true,
            ])
            ->getForm();
;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManagerInterface $em */
            $em = $this->entityManager;

            $em->persist($question);
            $em->flush();

            return $this->redirectToRoute('question_list');
        }

        // Renderowanie formularza w szablonie
        return $this->render('@Admin/question/open_question_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

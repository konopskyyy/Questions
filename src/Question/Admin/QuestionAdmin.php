<?php

declare(strict_types=1);

namespace App\Question\Admin;

use App\Question\Entity\ClosedQuestion;
use App\Question\Entity\Enum\QuestionType;
use App\Question\Entity\OpenQuestion;
use App\Question\Entity\QuestionMetadata;
use App\Question\Entity\QuestionTag;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class QuestionAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'question';
    protected $baseRoutePattern = 'question';

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('body')
            ->add('type')
            ->add('status');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('body')
            ->add('type')
            ->add('status')
            ->add('metadata.createdAt', null, [
                'label' => 'Created At',
                'sortable' => true,
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureActionButtons(array $buttonList, $action, ?object $object = null): array
    {
        $buttonList = parent::configureActionButtons($buttonList, $action, $object);

        if ($object && $this->canAccept($object)) {
            $buttonList['accept'] = [
                'template' => 'admin/question/show__action_accept.html.twig',
            ];
        }

        $buttonList['open_question_create'] = [
            'template' => 'admin/question/list__action_open_question_create.html.twig',
        ];

        return $buttonList;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection->add('open_question_create', 'open_question_create');
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('body')
            ->add('type', ChoiceType::class, [
                'choices' => $this->getQuestionTypeChoices(),
                'label' => 'Type',
            ])
            ->add('metadata', ModelType::class, [
                'class' => QuestionMetadata::class,
                'required' => false,
                'label' => 'Metadata',
            ]);

        if ($this->getSubject() instanceof OpenQuestion) {
            $form->add('answer');
        }

        // todo sprawdzic czemu nie ma danych
        if ($this->getSubject() instanceof ClosedQuestion) {
            $form->add('answerOptions', CollectionType::class, [
                'by_reference' => false,
                'required' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'admin_code' => 'admin.answer_option',
            ]);
        }

        $form
            ->add('images', CollectionType::class, [
                'by_reference' => false,
                'required' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'admin_code' => 'admin.question_image',
            ])
            ->add('tags', ModelType::class, [
                'class' => QuestionTag::class,
                'multiple' => true,
                'required' => false,
                'label' => 'Tags',
                // opcjonalnie:
                'property' => 'name', // jeśli chcesz wyświetlać nazwę taga
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
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('body');

        if ($this->getSubject() instanceof OpenQuestion) {
            $show->add('answer');
        }

        if ($this->getSubject() instanceof ClosedQuestion) {
            $show->add('answerOptions', null, [
                'label' => 'Answer Options',
                'template' => 'admin/question/field_answer_options.html.twig',
            ]);
        }

        $show
            ->add('type')
            ->add('status')
            ->add('metadata.createdAt', null, [
                'label' => 'Created At',
            ]) // todo kiedys autor
            ->add('images', null, [
                'label' => 'Images',
                'template' => 'admin/question/field_images.html.twig',
            ])
            ->add('tags', null, [
                'label' => 'Tags',
                'template' => 'admin/question/field_tags.html.twig',
            ])
            ->add('tips', null, [
                'label' => 'Tags',
                'template' => 'admin/question/field_tips.html.twig',
            ])
            ->add('urls', null, [
                'label' => 'Tags',
                'template' => 'admin/question/field_urls.html.twig',
            ]);
    }

    // todo te 2 metody moze juz niepotrzebne
    public function prePersist($object): void
    {
        if (null === $object->getStatus()) {
            $object->setStatus('draft');
        }
    }

    public function preUpdate($object): void
    {
        if (null === $object->getStatus()) {
            $object->setStatus('draft');
        }
    }

    public function canAccept($object): bool
    {
        $status = $object->getStatus();
        if ($status instanceof \App\Question\Entity\Enum\QuestionStatus) {
            return 'accepted' !== $status->value;
        }

        return 'accepted' !== $status;
    }

    private function getQuestionTypeChoices(): array
    {
        return array_combine(
            array_map(fn (QuestionType $type) => ucfirst(strtolower($type->name)), QuestionType::cases()),
            array_map(fn (QuestionType $type) => $type->value, QuestionType::cases())
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Question\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class QuestionMetadataAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'question_metadata';
    protected $baseRoutePattern = 'question_metadata';
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('createdAt')
            ->add('createdBy')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('createdAt')
            ->add('createdBy')
        ;
    }

    #[\Override]
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('createdBy', TextType::class, [
                'required' => false,
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('createdAt')
            ->add('createdBy')
        ;
    }
}

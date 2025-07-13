<?php

declare(strict_types=1);

namespace App\Question\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class QuestionTagAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'question_tag';
    protected $baseRoutePattern = 'question_tag';
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name')
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name')
            ->add('description', null
            )
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
        ;
    }
}

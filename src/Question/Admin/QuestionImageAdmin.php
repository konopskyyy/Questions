<?php

declare(strict_types=1);

namespace App\Question\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class QuestionImageAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'question_image';
    protected $baseRoutePattern = 'question_image';
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('url')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('url')
            ->add('name')
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('url')
            ->add('name')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('url')
            ->add('name')
        ;
    }
}

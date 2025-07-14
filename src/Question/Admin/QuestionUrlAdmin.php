<?php

declare(strict_types=1);

namespace App\Question\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class QuestionUrlAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'question_url';
    protected $baseRoutePattern = 'question_url';

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('url')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('url')
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('url')
            ->add('description')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('url')
            ->add('description')
        ;
    }
}

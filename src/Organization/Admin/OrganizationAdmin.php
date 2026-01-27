<?php

declare(strict_types=1);

namespace App\Organization\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class OrganizationAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('logo')
            ->add('taxId')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name')
            ->add('logo')
            ->add('taxId')
            ->add('createdAt')
            ->add('updatedAt')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name')
            ->add('logo')
            ->add('taxId')
            ->add('address.street')
            ->add('address.buildingNo')
            ->add('address.apartmentNo')
            ->add('address.city')
            ->add('address.postalCode')
            ->add('address.country')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('name')
            ->add('logo')
            ->add('taxId')
            ->add('address.street')
            ->add('address.buildingNo')
            ->add('address.apartmentNo')
            ->add('address.city')
            ->add('address.postalCode')
            ->add('address.country')
            ->add('recruiters', null, [
                'template' => 'admin/organization/field/recruiters.html.twig',
            ])
            ->add('candidates', null, [
                'template' => 'admin/organization/field/candidates.html.twig',
            ])
        ;
    }
}

<?php

declare(strict_types=1);

namespace App\Organization\Admin;

use App\Organization\Application\Command\UploadOrganizationLogo\DTO\UploadOrganizationLogoDTO;
use App\Organization\Application\Command\UploadOrganizationLogo\UploadOrganizationLogoCommand;
use App\Organization\Domain\Entity\Organization;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrganizationAdmin extends AbstractAdmin
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
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
            ->add('logo', null, [
                'template' => 'admin/organization/list/logo.html.twig',
            ])
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
            ->add('logo', FileType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Logo',
            ])
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
            ->add('logo', null, [
                'template' => 'admin/organization/field/logo.html.twig',
            ])
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

    protected function prePersist(object $object): void
    {
        $this->handleFileUpload($object);
    }

    protected function preUpdate(object $object): void
    {
        /* @var Organization $object */
        $this->handleFileUpload($object);
    }

    private function handleFileUpload(object $organization): void
    {
        $form = $this->getForm();
        /** @var UploadedFile|null $uploadedFile */
        $uploadedFile = $form->get('logo')->getData();

        if (!$uploadedFile) {
            return;
        }

        $content = file_get_contents($uploadedFile->getPathname());

        if (false === $content) {
            throw new \RuntimeException('Unable to read uploaded file.');
        }

        $mimeType = $uploadedFile->getMimeType();

        if (!$mimeType) {
            throw new \RuntimeException('Mime type not found in uploaded file.');
        }

        /* @var Organization $organization */
        $this->commandBus->dispatch(
            new UploadOrganizationLogoCommand(
                organizationId: $organization->getId(),
                uploadOrganizationLogoDTO: new UploadOrganizationLogoDTO(
                    file: base64_encode($content),
                    mimeType: $mimeType,
                ),
            )
        );
    }
}

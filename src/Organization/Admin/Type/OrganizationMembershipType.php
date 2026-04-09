<?php

declare(strict_types=1);

namespace App\Organization\Admin\Type;

use App\Organization\Domain\Entity\OrganizationMembership;
use App\Organization\Domain\Enum\OrganizationRole;
use App\User\Domain\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganizationMembershipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'User',
            ])
            ->add('role', EnumType::class, [
                'class' => OrganizationRole::class,
                'choice_label' => static fn (OrganizationRole $role): string => ucfirst($role->value),
                'label' => 'Role',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrganizationMembership::class,
        ]);
    }
}

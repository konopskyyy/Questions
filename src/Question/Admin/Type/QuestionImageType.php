<?php

declare(strict_types=1);

namespace App\Question\Admin\Type;

use App\Question\Entity\QuestionImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionImageType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Image name',
                'required' => false,
            ])->add('url', TextType::class, [
                'label' => 'Image URL',
                'required' => false,
            ])

        ;
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuestionImage::class,
        ]);
    }
}

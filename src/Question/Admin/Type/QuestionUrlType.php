<?php

declare(strict_types=1);
// src/Form/QuestionImageType.php

namespace App\Question\Admin\Type;

use App\Question\Entity\QuestionUrl;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionUrlType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => 'URL description',
                'required' => false,
            ])
            ->add('url', TextType::class, [
                'label' => 'URL',
                'required' => false,
            ])
        ;
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuestionUrl::class,
        ]);
    }
}

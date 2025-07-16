<?php

declare(strict_types=1);

namespace App\Question\Admin\Type;

use App\Question\Entity\AnswerOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerOptionType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('letter', TextType::class, [
                'label' => 'Litera odpowiedzi',
            ])
            ->add('body', TextType::class, [
                'label' => 'Treść odpowiedzi',
            ])
            ->add('isCorrect', CheckboxType::class, [
                'label' => 'Czy poprawna?',
                'required' => false,
            ])
        ;
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnswerOption::class,
        ]);
    }
}

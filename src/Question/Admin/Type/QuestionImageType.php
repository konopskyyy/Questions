<?php
declare(strict_types=1);
// src/Form/QuestionImageType.php
namespace App\Question\Admin\Type;

use App\Question\Entity\QuestionImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Image file',
                'required' => false,
            ])->add('url', TextType::class, [
                'label' => 'Image file',
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuestionImage::class,
        ]);
    }
}

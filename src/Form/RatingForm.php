<?php

namespace App\Form;

use App\Entity\Rating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('score', ChoiceType::class, [
                'label'   => 'Note',
                'choices' => [
                    '⭐ 1' => 1,
                    '⭐⭐ 2' => 2,
                    '⭐⭐⭐ 3' => 3,
                    '⭐⭐⭐⭐ 4' => 4,
                    '⭐⭐⭐⭐⭐ 5' => 5,
                ],
                'expanded' => true, // affiche des radio buttons
                'multiple' => false,
            ])
            ->add('comment', TextareaType::class, [
                'label'    => 'Commentaire',
                'required' => false,
                'attr'     => [
                    'rows'        => 4,
                    'placeholder' => 'Laissez un commentaire (optionnel)...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}

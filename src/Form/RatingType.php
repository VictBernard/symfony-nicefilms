<?php

namespace App\Form;

use App\Entity\Rating;
use App\Entity\Series;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('value', ChoiceType::class, [
            'choices' => [
                '0.5' => 1,
                '1' => 2,
                '1.5' => 3,
                '2.0' => 4,
                '2.5' => 5,
                '3.0' => 6,
                '3.5' => 7,
                '4.0' => 8,
                '4.5' => 9,
                '5' => 10
            ],
            'multiple' => false
        ])
        ->add('comment', TextareaType::class, [
            'required' => false,
            'empty_data' => '',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}

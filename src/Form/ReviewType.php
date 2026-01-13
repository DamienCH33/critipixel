<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

final class ReviewType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Review::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'constraints' => [new NotNull()],
                'label' => 'Note',
                'placeholder' => 'Choisissez une note',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
            ])
            ->add('comment', TextareaType::class, [
                'constraints' => [new Length([
                    'max' => 280,
                ])],
                'label' => 'Commentaire',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Commentaire',
                ],
            ]);
    }
}

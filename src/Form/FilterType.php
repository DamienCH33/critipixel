<?php

declare(strict_types=1);

namespace App\Form;

use App\List\VideoGameList\Filter;
use App\Model\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'label' => 'Rechercher',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher...',
                ],
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Tags',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => Tag::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'd-flex gap-2 flex-wrap',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Filter::class);
    }
}

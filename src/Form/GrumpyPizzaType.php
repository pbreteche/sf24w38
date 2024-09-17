<?php

namespace App\Form;

use App\Entity\GrumpyPizza;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrumpyPizzaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('composedWith', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'superName',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
        if (!$options['without_size']) {
            $builder->add('size');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GrumpyPizza::class,
            'without_size' => false,
        ])->setAllowedTypes('without_size', 'boolean');
    }
}

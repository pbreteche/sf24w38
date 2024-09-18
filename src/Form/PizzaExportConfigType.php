<?php

namespace App\Form;

use App\Entity\ApiUser;
use App\Entity\PizzaExportConfig;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PizzaExportConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('owners', EntityType::class, [
                'class' => ApiUser::class,
                'choice_label' => 'label',
                'multiple' => true,
            ])
            ->add('fields', TextType::class, [
                'required' => false,
            ])
        ;

        $builder->get('fields')
            ->addViewTransformer(new CallbackTransformer(
                fn ($normalizedData) => implode(',', $normalizedData),
                fn ($viewData) => explode(',', $viewData),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PizzaExportConfig::class,
        ]);
    }
}

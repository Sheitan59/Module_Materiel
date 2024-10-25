<?php

namespace App\Form;

use App\Entity\TVA;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TvaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Nom de la Taxe :',
                'attr' => [
                    'placeholder' => 'Ex : Tva 20%'
                ]
            ])
            ->add('valeur', NumberType::class, [
                'label' => 'Coefficient de taxe :',
                'attr' => [
                    'placeholder' => 'Ex : 0.2 pour 20 %'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TVA::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\TVA;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Materiel :',
                'attr' => [
                    'placeholder' => 'Nom du Materiel'
                ]
            ])
            ->add('prixHt', NumberType::class, [
                'label' => 'Prix Hors Taxe : ',
                'attr' => [
                    'placeholder' => ''
                ]
            ])
            ->add('prixTtc', NumberType::class, [
                'label' => 'Prix TTC : ',
                'attr' => [
                    'placeholder' => ''
                ]
            ])
            ->add('quantite', NumberType::class, [
                'label' => 'Stock :',
                'attr' => [
                    'placeholder' => ''
                ]
            ])
            ->add('dateDeCreation', DateType::class, [
               'widget' => 'single_text',
                'input'  => 'datetime_immutable'
            ])
            ->add('tva', EntityType::class, [
                'class' => TVA::class,
                'choice_label' => 'libelle',
                'choice_value' => 'valeur',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}

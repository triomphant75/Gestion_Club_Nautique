<?php
// src/Form/MaterielType.php

namespace App\Form;

use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeMateriel', TextType::class, [
                'label' => 'Type de Matériel',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('caracteristique', TextareaType::class, [
                'label' => 'Caractéristique',
                'attr' => ['class' => 'form-control mb-3', 'rows' => 3] // Limiter la hauteur du textarea
            ])
            ->add('numSerie', TextType::class, [
                'label' => 'Numéro de Série',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('dateMiseEnService', DateType::class, [
                'label' => 'Date de Mise en Service',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('etatMateriel', ChoiceType::class, [
                'label' => 'État du Matériel',
                'choices' => [
                    'Disponible' => Materiel::ETAT_DISPONIBLE,
                    'Loué' => Materiel::ETAT_LOUE,
                ],
                'attr' => ['class' => 'form-control mb-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}

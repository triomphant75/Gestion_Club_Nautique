<?php
// src/Form/PanneType.php

namespace App\Form;

use App\Entity\Panne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PanneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'Description de la Panne',
                'attr' => ['class' => 'form-control mb-3', 'rows' => 3] // Limiter la hauteur du textarea
            ])
            ->add('datePanne', DateType::class, [
                'label' => 'Date de la Panne',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('dateDebutReparation', DateType::class, [
                'label' => 'Date de Début de Réparation',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('dateFinReparation', DateType::class, [
                'label' => 'Date de Fin de Réparation',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('etatPanne', ChoiceType::class, [
                'label' => 'État de la Panne',
                'choices' => [
                    'Déclaré' => 'Déclaré',
                ],
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
                'attr' => ['class' => 'form-control mb-3', 'rows' => 3] // Limiter la hauteur du textarea
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panne::class,
        ]);
    }
}

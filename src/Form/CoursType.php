<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Forfait;
use App\Entity\Materiel;
use App\Entity\Moniteur;
use App\Entity\Proprietaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('dateCours', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date du cours',
        ])
        ->add('heureDebutCours', TimeType::class, [
            'widget' => 'single_text',
            'label' => 'Heure de début',
        ])
        ->add('heureFinCours', TimeType::class, [
            'widget' => 'single_text',
            'label' => 'Heure de fin',
        ])
        ->add('etatCours', ChoiceType::class, [
            'choices' => [
                'Planifié' => 'Planifié',
                'En cours' => 'En cours',
                'Terminé' => 'Terminé',
                'Annulé' => 'Annulé',
            ],
            'label' => 'État du cours',
        ])
        ->add('nombreDePlace', null, [
            'label' => 'Nombre de places',
        ])
        
        ->add('moniteur', EntityType::class, [
            'class' => Moniteur::class,
            'choices' => $options['moniteurs'], // Utilisation des moniteurs actifs passés
            'choice_label' => function (Moniteur $moniteur) {
                return sprintf('%s %s - %s', $moniteur->getPrenomUser(), $moniteur->getUsername(), $moniteur->getSpecialite());
            },
            'label' => 'Moniteur',
            'placeholder' => 'Choisir un moniteur',
        ])

        
        ->add('materiels', EntityType::class, [
            'class' => Materiel::class,
            'choice_label' => 'typeMateriel', // Assure-toi que l'entité Materiel possède cette propriété.
            'multiple' => true,
            'expanded' => true, // Utilise des cases à cocher
            'label' => 'Matériaux disponibles',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
            'moniteurs' => [], // Option moniteurs par défaut vide
        ]);
    }
}

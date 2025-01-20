<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Location;
use App\Entity\Materiel;
use App\Entity\Paiement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nomClient',
                'label' => 'Client',
            ])
            ->add('materiel', EntityType::class, [
                'class' => Materiel::class,
                'choice_label' => 'typeMateriel',
                'label' => 'Matériel',
            ])
            ->add('dureeLocation', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Durée de la location',
            ])

            ->add('prixLocation', NumberType::class, [
                'label' => 'Prix de la location',
                'required' => true,
            ])

            ->add('prixLocationRemise', NumberType::class, [
                'label' => 'Prix avec remise (optionnel)',
                'required' => false, // PrixRemise n'est pas obligatoire
            ])

            ->add('dateLocation', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de la location',
            ])
            ->add('etatLocation', ChoiceType::class, [
                'choices' => [
                    'En Attente' => 'En Attente',
                    'En Cours' => 'En cours',
                    'Annulé' => 'Annulé',
                    'Terminé' => 'Terminée',
                ],
                'label' => 'État de la location',
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
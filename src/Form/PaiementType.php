<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Forfait;
use App\Entity\Location;
use App\Entity\Paiement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('montant', NumberType::class, [
            'label' => 'Montant',
            'disabled' => true,
        ])
        ->add('modePaiement', ChoiceType::class, [
            'choices' => [
                'Carte' => 'Carte',
                'Espèce' => 'Espèce',
                'Virement' => 'Virement',
            ],
            'label' => 'Mode de Paiement',
        ])
        ->add('statutPaiement', ChoiceType::class, [
            'choices' => [
                'En Attente' => 'En Attente',
                'Confirmé' => 'Confirmé',
                'Annulé' => 'Annulé',
            ],
            'label' => 'Statut du Paiement',
        ]);
}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Paiement::class,
    ]);
}
}
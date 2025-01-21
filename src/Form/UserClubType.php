<?php

namespace App\Form;

use App\Entity\UserClub;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { $builder
        ->add('username', TextType::class, [
            'label' => 'Nom d\'utilisateur',
        ])
        ->add('prenomUser', TextType::class, [
            'label' => 'Prénom',
        ])
        ->add('adresseUser', TextType::class, [
            'label' => 'Adresse',
        ])
        ->add('emailUser', EmailType::class, [
            'label' => 'Email',
        ])
        ->add('statutUser', ChoiceType::class, [
            'label' => 'Statut',
            'choices' => [
                'Disponible' => 'Disponible',
                'Indisponible' => 'Indisponible',
            ],
        ])
        ->add('roles', ChoiceType::class, [
            'label' => 'Rôle',
            'choices' => [
                'Gestionnaire IT' => 'ROLE_GESTIONNAIRE',
                'Moniteur' => 'ROLE_MONITEUR',
                'Compagne Propriétaire' => 'ROLE_COMPAGNE_PROPRIETAIRE',
                'Propriétaire' => 'ROLE_PROPRIETAIRE',
            ],
            'multiple' => true,
            'expanded' => true,
        ])
        ->add('password', PasswordType::class, [
            'label' => 'Mot de passe',
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserClub::class,
        ]);
    }
}

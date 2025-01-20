<?php
// src/Form/AddMoniteurType.php
namespace App\Form;

use App\Entity\Moniteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AddMoniteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom',
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
            ->add('diplome', TextType::class, [
                'label' => 'Diplôme',
            ])
            ->add('specialite', TextType::class, [
                'label' => 'Spécialité',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true, // Le mot de passe est obligatoire pour l'ajout
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Moniteur::class,
        ]);
    }
}
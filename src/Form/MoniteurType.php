<?php
// src/Form/MoniteurType.php
namespace App\Form;

use App\Entity\Moniteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class MoniteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom',
                'disabled' => $options['is_edit'] ?? false,
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
                'required' => false,
                'empty_data' => '',
            ])
            // Champ pour modifier le statut
            ->add('statutUser', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Disponible' => 'Disponible',
                    'Indisponible' => 'Indisponible',
        
                ],
                'required' => false, // Pas obligatoire lors de la modification
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $form->getData();

                // Vous pouvez valider les données ici si nécessaire
                if (null === $data->getUsername() || '' === $data->getUsername()) {
                    $form->get('username')->setData($data->getUsername());
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Moniteur::class,
            'is_edit' => false,
        ]);
    }
}
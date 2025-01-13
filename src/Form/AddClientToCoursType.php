<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Client;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\ClientRepository;
use App\Entity\Participation;

class AddClientToCoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {$cours = $options['cours'];

        // Champ select pour choisir un client unique
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nomClient',
                'multiple' => false,  // Un seul client sélectionné
                'expanded' => false,  // Pas de cases à cocher, juste un select
                'query_builder' => function (ClientRepository $repo) {
                    return $repo->createQueryBuilder('c')
                        ->orderBy('c.nomClient', 'ASC');
                },
            ])
            // Champ pour la date d'inscription (par défaut, c'est la date actuelle)
            ->add('dateInscriptionCours', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(), // Date d'inscription par défaut à aujourd'hui
                'required' => true,
            ])
            // Champ pour le statut (par défaut, "Inscrit")
            ->add('statutParticipant', ChoiceType::class, [
                'choices' => [
                    'Inscrit' => 'Inscrit',
                    'Annulé' => 'Annulé',
                ],
                'data' => 'Inscrit', // Statut par défaut
                'required' => true,
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter Clients',
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participation::class, // Associe l'entité Participation
            'cours' => null, // On passe le cours pour faire un lien dans la base de données
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Entity\Moniteur;
use App\Entity\UserClub;
use App\Form\AddClientToCoursType;
use App\Entity\Participation;

class CoursController extends AbstractController
{
    #[Route('/cours', name: 'cours_index')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        // Récupérer tous les cours
        $cours = $em->getRepository(Cours::class)->findAll();

        // Créer un nouvel objet Cours pour le formulaire
        $newCours = new Cours();
        // Récupérer tous les UserClub avec statut "actif"
        $userClubsActifs = $em->getRepository(UserClub::class)->findBy([
            'statutUser' => 'Actif', // Filtrer par statutUser
        ]);

        // Filtrer pour ne garder que les Moniteurs
        $moniteursActifs = array_filter($userClubsActifs, function($userClub) {
            return in_array('ROLE_MONITEUR', $userClub->getRoles());
        });

        // Créer le formulaire et passer l'option 'moniteurs'
        $form = $this->createForm(CoursType::class, $newCours, [
            'moniteurs' => $moniteursActifs, // Passer les moniteurs actifs au formulaire
        ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newCours);
            $em->flush();

            return $this->redirectToRoute('cours_index');
        }

            // Préparer le formulaire d'ajout de client pour chaque cours
            $addClientForms = [];
            foreach ($cours as $coursItem) {
                // Créez un formulaire spécifique pour chaque cours
                $addClientForms[$coursItem->getId()] = $this->createForm(AddClientToCoursType::class, null, [
                    'cours' => $coursItem
                ])->createView();
            }


        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
            'addClientForms' => $addClientForms,

        ]);
    }



    #[Route('/cours/{id}', name: 'cours_show')]
    public function show(Cours $cours): Response
    {
        return $this->render('cours/show.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/cours/{id}/participants', name: 'cours_participants')]
    public function participants(Cours $cours, EntityManagerInterface $em): Response
    {
        // Récupérer les participations associées au cours
        $participants = $em->getRepository(Participation::class)->findBy(['cours' => $cours]);

        return $this->render('cours/participants.html.twig', [
            'cours' => $cours,
            'participants' => $participants,
        ]);
    }


    #[Route('/cours/{id}/add-clients', name: 'cours_add_clients')]
    public function addClientsToCours(
        Cours $cours, // Le cours est récupéré automatiquement grâce à l'ID dans l'URL
        Request $request,
        EntityManagerInterface $em
    ): Response {
        // Créez le formulaire
        $form = $this->createForm(AddClientToCoursType::class, null, [
            'cours' => $cours // Passez le cours au formulaire
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les clients sélectionnés
            $clients = $form->get('client')->getData();
            $dateInscription = $form->get('dateInscriptionCours')->getData();
            $statut = $form->get('statutParticipant')->getData();

            
    
            // Vérification pour chaque client ajouté
            foreach ($clients as $client) {
                // Vérifier si le client est déjà inscrit à ce cours
            $existingParticipation = $em->getRepository(Participation::class)->findOneBy([
                'cours' => $cours,
                'client' => $client
            ]);

            // Si le client n'est pas encore inscrit, on crée une nouvelle participation
            if (!$existingParticipation) {
                $participation = new Participation();
                $participation->setCours($cours);
                $participation->setClient($client);
                $participation->setDateInscriptionCours($dateInscription);  // Date d'inscription actuelle
                $participation->setStatutParticipant( $statut);  // Statut par défaut "Inscrit"

                // Persister la participation
                $em->persist($participation);
            } else {
                // Si le client est déjà inscrit, on peut choisir de l'ignorer ou mettre à jour les informations
                // Par exemple, on peut juste vérifier le statut ici si besoin.
            }
        }
    
            // Sauvegarder les modifications
            $em->flush();
    
            // Message de succès et redirection
            $this->addFlash('success', 'Clients ajoutés au cours avec succès.');
            return $this->redirectToRoute('cours_participants', ['id' => $cours->getId()]);
        }
    
        return $this->render('cours/add_clients.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }
    
    



}

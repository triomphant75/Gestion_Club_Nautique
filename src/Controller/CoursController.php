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

        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
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
    public function participants(Cours $cours): Response
    {
        // Récupérer les participations associées au cours
        $participants = $cours->getParticipations();

        return $this->render('cours/participants.html.twig', [
            'cours' => $cours,
            'participants' => $participants,
        ]);
    }
}

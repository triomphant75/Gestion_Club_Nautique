<?php
// src/Controller/MaterielController.php

namespace App\Controller;

use App\Entity\Materiel;
use App\Entity\Panne;
use App\Entity\Location;
use App\Form\MaterielType;
use App\Form\PanneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaterielController extends AbstractController
{
    #[Route('/materiel', name: 'materiel_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $materiels = $entityManager->getRepository(Materiel::class)->findBy(['etatMateriel' => [Materiel::ETAT_DISPONIBLE, Materiel::ETAT_HORS_SERVICE, Materiel::ETAT_LOUE]]);

        return $this->render('materiel/index.html.twig', [
            'materiels' => $materiels,
        ]);
    }

    #[Route('/materiel/new', name: 'materiel_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($materiel);
            $entityManager->flush();

            return $this->redirectToRoute('materiel_index');
        }

        return $this->render('materiel/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/materiel/{id}/edit', name: 'materiel_edit')]
    public function edit(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('materiel_index');
        }

        return $this->render('materiel/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/materiel/{id}/delete', name: 'materiel_delete')]
    public function delete(Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        // Supprimer les références au matériel dans les autres tables
        $locations = $entityManager->getRepository(Location::class)->findBy(['materiel' => $materiel]);
        foreach ($locations as $location) {
            $entityManager->remove($location);
        }

        $pannes = $entityManager->getRepository(Panne::class)->findBy(['materiel' => $materiel]);
        foreach ($pannes as $panne) {
            $entityManager->remove($panne);
        }

        // Supprimer le matériel
        $entityManager->remove($materiel);
        $entityManager->flush();

        return $this->redirectToRoute('materiel_index');
    }

    #[Route('/materiel/{id}/panne', name: 'materiel_panne')]
    public function declarePanne(Request $request, Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        $panne = new Panne();
        $panne->setMateriel($materiel);
        $form = $this->createForm(PanneType::class, $panne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panne);
            $entityManager->flush(); // Persist the panne first
            $materiel->setEtatMateriel(Materiel::ETAT_EN_PANNE);
            $entityManager->flush(); // Update the materiel status

            return $this->redirectToRoute('panne_index');
        }

        return $this->render('materiel/panne.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

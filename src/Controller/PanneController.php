<?php
// src/Controller/PanneController.php

namespace App\Controller;

use App\Entity\Panne;
use App\Entity\Materiel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PanneController extends AbstractController
{
    #[Route('/panne', name: 'panne_index')]
    public function ConsulterPanne(EntityManagerInterface $entityManager): Response
    {
        $pannes = $entityManager->getRepository(Panne::class)->findBy(['etatPanne' => ['Déclaré', 'En cours', 'Hors Service']]);

        return $this->render('panne/index.html.twig', [
            'pannes' => $pannes,
        ]);
    }

    #[Route('/panne/{id}/resolve', name: 'panne_resolve')]
    public function resolvePanne(Panne $panne, EntityManagerInterface $entityManager): Response
    {
        $panne->setEtatPanne('Résolu');
        $panne->getMateriel()->setEtatMateriel(Materiel::ETAT_DISPONIBLE); // Set materiel status to Disponible
        $entityManager->flush();

        return $this->redirectToRoute('panne_index');
    }

    #[Route('/panne/{id}/hors_service', name: 'panne_hors_service')]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function horsServicePanne(Panne $panne, EntityManagerInterface $entityManager): Response
    {
        $panne->setEtatPanne('Hors Service');
        $panne->getMateriel()->setEtatMateriel(Materiel::ETAT_HORS_SERVICE); // Set materiel status to Hors Service
        $entityManager->flush();

        return $this->redirectToRoute('panne_index');
    }
}

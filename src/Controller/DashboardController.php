<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Materiel;
use App\Entity\Cours;
use App\Entity\Camping;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function consulterDashboard(EntityManagerInterface $entityManager): Response
    {
        // Récupérer uniquement les cours planifiés
        $coursPlanifies = $entityManager->getRepository(Cours::class)->findBy([
            'etatCours' => 'Planifié', // Remplacez par le critère adapté à votre base
        ]);


        $nombreMaterielsDisponibles = $entityManager->getRepository(Materiel::class)->count([
            'etatMateriel' => 'Disponible' // Assurez-vous que cette valeur correspond bien à l'état dans votre base
        ]);
        // Récupérer le nombre total de clients
        $nombreClients = $entityManager->getRepository(Client::class)->count([]);

         // Récupérer les campings partenaires
          $nombreCampings= $entityManager->getRepository(Camping::class)->count([]);
        // Retourner les données au template Twig
        return $this->render('dashboard/index.html.twig', [
            'nombreClients' => $nombreClients,
            'cours' => $coursPlanifies,
            'nombreMaterielsDisponibles' => $nombreMaterielsDisponibles,
            'nombreCampings' => $nombreCampings,
        ]);
    }
}
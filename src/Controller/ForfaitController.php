<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Forfait;
use App\Repository\ClientRepository;
use App\Repository\ForfaitRepository;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;

class ForfaitController extends AbstractController
{
    #[Route('/client/{id}/forfait/create', name: 'app_forfait_create', methods: ['POST'])]
public function createForfait(int $id, ClientRepository $clientRepository, Request $request, EntityManagerInterface $entityManager): Response
{
    // Récupérer le client
    $client = $clientRepository->find($id);
    if (!$client) {
        throw $this->createNotFoundException('Client introuvable.');
    }

    // Créer un forfait temporaire
    $forfait = new Forfait();
    $forfait->setClient($client);
    $forfait->setTypeForfait($request->request->get('typeForfait'));
    $forfait->setNombreSeance((int)$request->request->get('nombreSeance'));
    $forfait->setPrixForfait((float)$request->request->get('prixForfait'));
    $forfait->setPrixRemiseForfait((float)$request->request->get('prixRemiseForfait'));
    $forfait->setDateDebut(new \DateTime($request->request->get('dateDebut')));
    $forfait->setDateExpiration(new \DateTime($request->request->get('dateExpiration')));
    $forfait->setIsConfirmed(false); // Marquer comme temporaire

    // Persister et flusher le forfait temporaire
    $entityManager->persist($forfait);
    $entityManager->flush();

    // Rediriger vers la page de paiement avec l'ID du forfait temporaire
    return $this->redirectToRoute('app_paiement_create', ['id' => $client->getId(), 'forfait_id' => $forfait->getId()]);
}

    


#[Route('/forfait/{id}/edit', name: 'app_forfait_edit', methods: ['GET', 'POST'])]
public function editForfait(
    int $id,
    ForfaitRepository $forfaitRepository,
    Request $request,
    EntityManagerInterface $entityManager
): Response {
    $forfait = $forfaitRepository->find($id);
    if (!$forfait) {
        throw $this->createNotFoundException('Forfait non trouvé.');
    }

    // Si la méthode est POST, mettre à jour les données du forfait
    if ($request->isMethod('POST')) {
        $forfait->setTypeForfait($request->request->get('typeForfait'));
        $forfait->setNombreSeance((int)$request->request->get('nombreSeance'));
        $forfait->setPrixForfait((float)$request->request->get('prixForfait'));
        $forfait->setPrixRemiseForfait((float)$request->request->get('prixRemiseForfait'));
        $forfait->setDateDebut(new \DateTime($request->request->get('dateDebut')));
        $forfait->setDateExpiration(new \DateTime($request->request->get('dateExpiration')));

        $entityManager->flush();
        
        $this->addFlash('success', 'Le forfait a été mis à jour avec succès.');
        return $this->redirectToRoute('client_profile', ['id' => $forfait->getClient()->getId()]);
    }

    return $this->render('forfait/edit.html.twig', [
        'forfait' => $forfait,
    ]);
}
    
    
}

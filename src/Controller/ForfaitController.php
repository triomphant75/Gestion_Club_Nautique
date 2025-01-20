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
use App\Entity\TypeForfait;

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


    // Récupérer la valeur de l'énumération depuis la requête et la convertir en instance de TypeForfait
    $typeForfait = $request->request->get('typeForfait');
    if ($typeForfait) {
        // Convertir la chaîne de caractères en instance de l'énumération TypeForfait
        $forfait->setTypeForfait(TypeForfait::from($typeForfait)); // Conversion en instance d'énumération
    }

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

    
    
}

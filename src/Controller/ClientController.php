<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Paiement;

class ClientController extends AbstractController
{
    private $clientRepository;
    private $entityManager;

    public function __construct(ClientRepository $clientRepository, EntityManagerInterface $entityManager)
    {
        $this->clientRepository = $clientRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/client', name: 'app_client_index')]
    public function index(): Response
    {
        $clients = $this->clientRepository->findAll();
        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/client/new', name: 'app_client_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $client = new Client();
        $client->setNomClient($request->request->get('nomClient'));
        $client->setPrenomClient($request->request->get('prenomClient'));
        $client->setAdresseClient($request->request->get('adresseClient'));
        $client->setEmailClient($request->request->get('emailClient'));
        $client->setNiveauClient($request->request->get('niveauClient'));
        $client->setTelClient($request->request->get('TelClient'));
        $client->setDateInscriptionClient(new \DateTimeImmutable($request->request->get('DateInscriptionClient')));

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_client_index');
    }

    #[Route('/client/{id}/edit', name: 'app_client_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Client $client): Response
    {
        $client->setNomClient($request->request->get('nomClient'));
        $client->setPrenomClient($request->request->get('prenomClient'));
        $client->setAdresseClient($request->request->get('adresseClient'));
        $client->setEmailClient($request->request->get('emailClient'));
        $client->setNiveauClient($request->request->get('niveauClient'));
        $client->setTelClient($request->request->get('TelClient'));
        $client->setDateInscriptionClient(new \DateTimeImmutable($request->request->get('DateInscriptionClient')));

        $this->entityManager->flush();

        return $this->redirectToRoute('app_client_index');
    }

    #[Route('/client/{id}', name: 'app_client_delete', methods: ['POST'])]
public function delete(Client $client): Response
{
    $this->entityManager->remove($client);
    $this->entityManager->flush();

    return $this->redirectToRoute('app_client_index');
}


    #[Route('/client/{id}/profile', name: 'client_profile', methods: ['GET','POST'])]
    public function profile(Client $client): Response
    {
        return $this->render('client/profile.html.twig', [
            'client' => $client,
            'forfaits' => $client->getForfaits(),
            'paiements' => $client->getPaiements(),
        ]);
    }

    #[Route('/paiement/{id}/facture', name: 'app_facture_show')]
    public function consulterFacture(Paiement $paiement): Response
    {
        // On récupère la facture associée au paiement
        $facture = $paiement->getFacture();
        
        // On vérifie que la facture existe
        if (!$facture) {
            throw $this->createNotFoundException('Aucune facture trouvée pour ce paiement.');
        }
    
        // On retourne la vue de la facture
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }
    }

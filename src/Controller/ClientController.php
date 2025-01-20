<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\CampingRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Paiement;
use App\Entity\TypeNiveauClient;

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
    public function index(CampingRepository $campingRepository): Response
    {
        $campings = $campingRepository->findAll();
        $clients = $this->clientRepository->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'campings' => $campings
        ]);
    }

    #[Route('/client/new', name: 'app_client_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CampingRepository $campingRepository): Response
    {
        // Récupérer l'ID du camping sélectionné
        $campingId = $request->request->get('campingId');
        $camp = null;

        if (!empty($campingId)) {
            // Rechercher le camping dans le repository
            $camp = $campingRepository->find($campingId);

            // Vérifier si le camping existe
            if (!$camp) {
                throw $this->createNotFoundException('Camping non trouvé');
            }
        }

        $client = new Client();
        $client->setNomClient($request->request->get('nomClient'));
        $client->setPrenomClient($request->request->get('prenomClient'));
        $client->setAdresseClient($request->request->get('adresseClient'));
        $client->setEmailClient($request->request->get('emailClient'));
        // Récupérer l'ID du niveau client
        $niveauClientId = $request->request->get('niveauClient');
        $niveauClientEnum = TypeNiveauClient::from($niveauClientId); // Conversion en énumération
        $client->setNiveauClient($niveauClientEnum);
        // Associer le camping s'il est sélectionné
        if ($camp) {
            $client->setCamping($camp);
        }        
        $client->setTelClient($request->request->get('TelClient'));

        $client->setDateInscriptionClient(new \DateTimeImmutable($request->request->get('DateInscriptionClient')));

        $this->entityManager->persist($client);
        $this->entityManager->flush();
         // Message de succès et redirection
         $this->addFlash('success', 'Client ajouté avec succès.');

        return $this->redirectToRoute('app_client_index');
    }

    #[Route('/client/{id}/edit', name: 'app_client_edit', methods: ['GET','POST'])]
public function edit(Request $request, Client $client, CampingRepository $campingRepository): Response
{

    if ($request->isMethod('POST')) {
        $client->setNomClient($request->request->get('nomClient'));
        $client->setPrenomClient($request->request->get('prenomClient'));
        $client->setAdresseClient($request->request->get('adresseClient'));
        $client->setEmailClient($request->request->get('emailClient'));
        // Récupérer l'ID du niveau client
        $niveauClientId = $request->request->get('niveauClient');
        $niveauClientEnum = TypeNiveauClient::from($niveauClientId); // Conversion en énumération
        $client->setNiveauClient($niveauClientEnum);

        $client->setTelClient($request->request->get('TelClient'));
        $client->setDateInscriptionClient(new \DateTimeImmutable($request->request->get('DateInscriptionClient')));
    
        $campingId = $request->request->get('campingId');
        if ($campingId) {
            $camping = $campingRepository->find($campingId);
            $client->setCamping($camping);
        } else {
            $client->setCamping(null);
        }
    
        // Enregistrer les modifications
        $this->entityManager->flush();
         // Message de succès et redirection
         $this->addFlash('success', 'Client modifié avec succès.');
    
        return $this->redirectToRoute('app_client_index');
    }

}




    #[Route('/client/{id}', name: 'app_client_delete', methods: ['POST'])]
public function delete(Client $client): Response
{
    $this->entityManager->remove($client);
    $this->entityManager->flush();
     // Message de succès et redirection
     $this->addFlash('success', 'Client supprimé avec succès.');

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

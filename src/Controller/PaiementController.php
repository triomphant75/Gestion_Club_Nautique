<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PaiementRepository;
use App\Repository\ForfaitRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClientRepository;
use App\Entity\Paiement;
use App\Entity\Forfait;
use App\Entity\Facture;

class PaiementController extends AbstractController

{

    #[Route('/client/{id}/paiement/create', name: 'app_paiement_create', methods: ['GET', 'POST'])]
public function creerPaiement(int $id, Request $request, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response
{
    // Récupérer le client
    $client = $clientRepository->find($id);
    if (!$client) {
        throw $this->createNotFoundException('Client introuvable.');
    }

    // Récupérer l'identifiant du forfait à partir de la requête
    $forfaitId = $request->query->get('forfait_id');
    if (!$forfaitId) {
        throw $this->createNotFoundException('Forfait introuvable. L\'identifiant du forfait est manquant.');
    }

    // Récupérer le forfait à partir de l'identifiant
    $forfait = $entityManager->getRepository(Forfait::class)->find($forfaitId);
    if (!$forfait || $forfait->getClient()->getId() !== $client->getId()) {
        throw $this->createNotFoundException('Forfait introuvable ou non associé au client.');
    }


    // Calculer le montant du forfait (prix avec ou sans remise)
    $montantForfait = $forfait->getPrixForfait();
    if ($client->getCamping() && $client->getCamping()->getRemiseCamping()) {
        // Appliquer la remise si le client appartient à un camping
        $remise = $client->getCamping()->getRemiseCamping();
        $montantForfait = $montantForfait - ($montantForfait * ($remise/100));
    }

    // Créer le paiement
    if ($request->isMethod('POST')) {
        $paiement = new Paiement();
        $paiement->setClient($client);
        $paiement->setMontant((float) $request->request->get('montant'));
        $paiement->setModePaiement($request->request->get('modePaiement'));
        $paiement->setStatutPaiement($request->request->get('statutPaiement'));
        $paiement->setDatePaiement(new \DateTimeImmutable());
        $paiement->setForfait($forfait); // Lier le paiement au forfait

        // Mettre à jour le statut du forfait
        $forfait->setIsConfirmed(true);

        // Persister et flusher le paiement et le forfait
        $entityManager->persist($paiement);
        $entityManager->flush();

        // Créer la facture
        $facture = new Facture();
        $facture->setNumFacture(uniqid()); // Générer un numéro de facture unique
        $facture->setMontantTotal($paiement->getMontant());
        $facture->setAdresseEtablissement('IMC'); // Remplacez par l'adresse réelle
        $facture->setDateFacture(new \DateTimeImmutable());
        $facture->setPaiement($paiement);

        // Persister et flusher la facture
        $entityManager->persist($facture);
        $entityManager->flush();

        // Ajouter un message flash
        $this->addFlash('success', 'Le paiement a été effectué avec succès et la facture a été générée.');

        // Rediriger vers la page de profil client après l'ajout du paiement
        return $this->redirectToRoute('client_profile', ['id' => $client->getId()]);
    }

    // Passer à la vue avec les informations nécessaires
    return $this->render('paiement/create.html.twig', [
        'client' => $client,
        'forfait' => $forfait, // Passer le forfait temporaire
        //'montant' => $forfait->getPrixForfait(), // Passer le montant du forfait
        'montant' =>  $montantForfait, // Passer le montant du forfait

    ]);
}

#[Route('/paiement', name: 'paiement_index', methods: ['GET'])]
    public function consulterListePaiement(PaiementRepository $paiementRepository): Response
    {
        $paiements = $paiementRepository->findAll();
        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiements,
        ]);
    }

   
    
}

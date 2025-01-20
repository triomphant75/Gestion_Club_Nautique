<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Paiement;
use App\Form\LocationType;
use App\Form\PaiementType;
use App\Repository\LocationRepository;
use App\Entity\Facture;

class LocationController extends AbstractController
{
    // Afficher la liste des locations
    #[Route('/location', name: 'location_index', methods: ['GET', 'POST'])]
    public function index(Request $request,LocationRepository $locationRepository, EntityManagerInterface $entityManager): Response
    {
        $locations = $locationRepository->findAll();

        // Créer une nouvelle location
        $location = new Location();
        $formLocation = $this->createForm(LocationType::class, $location);
    
        $formLocation->handleRequest($request);
    
        if ($formLocation->isSubmitted() && $formLocation->isValid()) {

            $location->setEtatLocation('En Attente');
            $entityManager->persist($location);
            $entityManager->flush();
    
            $this->addFlash('success', 'Location ajoutée avec succès.');
    
             // Rediriger vers la page de paiement pour cette location
        return $this->redirectToRoute('location_paiement_create', ['id' => $location->getId()]);
        }
        return $this->render('location/index.html.twig', [
            'locations' => $locations,
            'formLocation' => $formLocation->createView(), 
        ]);
    }

    // Créer une nouvelle location
    #[Route('/location/create', name: 'location_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $location = new Location();
        $formLocation = $this->createForm(LocationType::class, $location);
    
        $formLocation->handleRequest($request);
    
        if ($formLocation->isSubmitted() && $formLocation->isValid()) {

            $location->setEtatLocation('En Attente');
            $entityManager->persist($location);
            $entityManager->flush();
    
            $this->addFlash('success', 'Location ajoutée avec succès.');
    
             // Rediriger vers la page de paiement pour cette location
        return $this->redirectToRoute('location_paiement_create', ['id' => $location->getId()]);
        }
    
        return $this->render('location/create.html.twig', [
            'formLocation' => $formLocation->createView(), // Changer ici pour correspondre à "formLocation"
        ]);
    }

    // Modifier une location existante
    #[Route('/location/{id}/edit', name: 'location_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, LocationRepository $locationRepository, EntityManagerInterface $entityManager): Response
    {
        $location = $locationRepository->find($id);

        if (!$location) {
            throw $this->createNotFoundException('Location introuvable.');
        }

        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Location mise à jour avec succès.');

            return $this->redirectToRoute('location_index');
        }

        return $this->render('location/edit.html.twig', [
            'form' => $form->createView(),
            'location' => $location,
        ]);
    }

    // Gérer le paiement d'une location et créer une facture
#[Route('/location/{id}/paiement/create', name: 'location_paiement_create', methods: ['GET', 'POST'])]
public function createPaiement(
    int $id,
    Request $request,
    LocationRepository $locationRepository,
    EntityManagerInterface $entityManager
): Response {
    // Récupérer la location
    $location = $locationRepository->find($id);
    if (!$location) {
        throw $this->createNotFoundException('Location introuvable.');
    }

    // Si la location est partielle, poursuivre avec le paiement
    if ($location->getEtatLocation() !== 'En Attente') {
        throw $this->createNotFoundException('Location non partielle, impossible de procéder au paiement.');
    }

    // Récupérer le montant de la location
    $montant = $location->getPrixLocation();

    // Créer le formulaire de paiement
    $form = $this->createForm(PaiementType::class);
    $form->handleRequest($request);

    // Si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Créer un paiement pour cette location
        $paiement = new Paiement();
        $paiement->setClient($location->getClient());
        $paiement->setMontant($montant);
        $paiement->setModePaiement($form->get('modePaiement')->getData());
        $paiement->setStatutPaiement($form->get('statutPaiement')->getData());
        $paiement->setDatePaiement(new \DateTimeImmutable());

        // Associer ce paiement à la location
        $paiement->setLocation($location);

        // Créer la facture associée à ce paiement
        $facture = new Facture();
        $facture->setNumFacture(uniqid());
        $facture->setMontantTotal($paiement->getMontant());
        $facture->setAdresseEtablissement('Adresse de l\'établissement');
        $facture->setDateFacture(new \DateTimeImmutable());

        // Associer la facture au paiement
        $facture->setPaiement($paiement);

        // Sauvegarder les entités : paiement, facture et location
        $entityManager->persist($paiement);
        $entityManager->persist($facture);
        $entityManager->flush();

        // Mettre à jour l'état de la location à "Confirmée"
        $location->setEtatLocation('Confirmée');  // ou "En cours"
        $entityManager->persist($location);
        $entityManager->flush();

        // Ajouter un message de succès
        $this->addFlash('success', 'Le paiement a été effectué et la facture générée avec succès.');

        // Rediriger vers la liste des locations
        return $this->redirectToRoute('location_index');
    }

    // Vérifier si le bouton "Annuler" est soumis pour supprimer la location partielle
    if ($request->isMethod('POST') && $request->request->get('cancel')) {
       // Débogage
        dump('Annulation en cours'); // Ajout temporaire
        dump($request->request->all());
        die(); // Arrêtez l'exécution ici pour voir le résultat dans Symfony Debug Toolbar

        // Supprimer la location
        $entityManager->remove($location);
        $entityManager->flush();

        $this->addFlash('success', 'La location a été annulée.');

        // Rediriger vers la liste des locations
        return $this->redirectToRoute('location_index');
    }

    return $this->render('location/paiement_create.html.twig', [
        'location' => $location,
        'montant' => $montant,
        'form' => $form->createView(),
    ]);
}

}






































































































































































































































































































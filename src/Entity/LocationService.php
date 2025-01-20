<?php

namespace App\Entity;

use App\Entity\Location;
use App\Entity\Paiement;
use Doctrine\ORM\EntityManagerInterface;

class LocationService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function confirmerLocation(Location $location): void
    {
        if ($location->getEtatLocation() !== 'En Attente') {
            throw new \LogicException('Seules les locations en attente peuvent être confirmées.');
        }

        $location->setEtatLocation('En Cours');

        // Générer un paiement pour la location
        $paiement = new Paiement();
        $paiement->setMontant($location->getPrixLocation());
        $paiement->setDatePaiement(new \DateTimeImmutable());
        $paiement->setModePaiement('Carte');
        $paiement->setStatutPaiement('Confirmé');
        $paiement->setLocation($location);
        $paiement->setClient($location->getClient());

        $this->em->persist($paiement);
        $this->em->flush();
    }

    public function annulerLocation(Location $location): void
    {
        if ($location->getEtatLocation() === 'En Cours' || $location->getEtatLocation() === 'Terminée') {
            throw new \LogicException('Impossible d\'annuler une location en cours ou terminée.');
        }

        $location->setEtatLocation('Annulée');
        $this->em->flush();
    }

    public function terminerLocation(Location $location): void
    {
        if ($location->getEtatLocation() !== 'En Cours') {
            throw new \LogicException('Seules les locations en cours peuvent être terminées.');
        }

        $location->setEtatLocation('Terminée');
        $this->em->flush();
    }
}
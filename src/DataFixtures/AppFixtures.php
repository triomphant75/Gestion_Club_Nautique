<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Client;
use App\Entity\Camping;
use App\Entity\Cours;
use App\Entity\Facture;
use App\Entity\Forfait;
use App\Entity\Location;
use App\Entity\Materiel;
use App\Entity\Moniteur;
use App\Entity\Paiement;
use App\Entity\Panne;
use App\Entity\Participation;
use App\Entity\UtilisationForfait;
use App\Entity\UserClub;
use App\Entity\Proprietaire;
use \DateTimeImmutable;
use \DateTimeInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création de données pour les entités Camping et Clients
        $campingsData = [
            [
                'nom' => 'Camping Soleil',
                'adresse' => 'Route de la Plage, Marseille',
                'contact' => 'contact@campingsoleil.com',
                'remise' => 10.5,
            ],
            // ... (autres campings)
        ];

        $clientsData = [
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'adresse' => '10 rue de Paris, Nancy',
                'email' => 'jean.dupont@example.com',
                'niveau' => 'Débutant',
                'telephone' => '07 34 12 00 65',
                'camping' => 0, // Index pour relier à un camping
            ],
            // ... (autres clients)
        ];

        $campings = [];
        foreach ($campingsData as $data) {
            $camping = new Camping();
            $camping->setNomCamping($data['nom']);
            $camping->setAdresseCamping($data['adresse']);
            $camping->setContactCamping($data['contact']);
            $camping->setRemiseCamping($data['remise']);

            $manager->persist($camping);
            $campings[] = $camping;
        }

        $clients = [];
        foreach ($clientsData as $data) {
            $client = new Client();
            $client->setNomClient($data['nom']);
            $client->setPrenomClient($data['prenom']);
            $client->setAdresseClient($data['adresse']);
            $client->setDateInscriptionClient(new DateTimeImmutable());
            $client->setEmailClient($data['email']);
            $client->setNiveauClient($data['niveau']);
            $client->setTelClient($data['telephone']);
            $client->setCamping($campings[$data['camping']]);

            $manager->persist($client);
            $clients[] = $client;
        }

        // Création de Propriétaires
        $proprietairesData = [
            [
                'username' => 'proprietaire1',
                'roles' => ['ROLE_PROPRIETAIRE'],
                'password' => '$2y$13$eImiTXuWVxfM37uY4JANjQ==', // Password hash for 'password'
                'prenomUser' => 'Alice',
                'adresseUser' => '456 Rue de la Paix',
                'emailUser' => 'alice.smith@example.com',
                'statutUser' => 'Actif',
            ],
            // ... (autres propriétaires)
        ];

        $proprietaires = [];
        foreach ($proprietairesData as $data) {
            $proprietaire = new Proprietaire();
            $proprietaire->setUsername($data['username']);
            $proprietaire->setRoles($data['roles']);
            $proprietaire->setPassword($data['password']);
            $proprietaire->setPrenomUser($data['prenomUser']);
            $proprietaire->setAdresseUser($data['adresseUser']);
            $proprietaire->setEmailUser($data['emailUser']);
            $proprietaire->setStatutUser($data['statutUser']);

            $manager->persist($proprietaire);
            $proprietaires[] = $proprietaire;
        }

        // Création de Moniteurs
        $moniteursData = [
            [
                'diplome' => 'Brevet d\'État',
                'specialite' => 'Ski',
                'username' => 'moniteur1',
                'roles' => ['ROLE_MONITEUR'],
                'password' => '$2y$13$eImiTXuWVxfM37uY4JANjQ==', // Password hash for 'password'
                'prenomUser' => 'Bob',
                'adresseUser' => '789 Rue de la Montagne',
                'emailUser' => 'bob.moniteur@example.com',
                'statutUser' => 'Actif',
            ],
            // ... (autres moniteurs)
        ];

        $moniteurs = [];
        foreach ($moniteursData as $data) {
            $moniteur = new Moniteur();
            $moniteur->setDiplome($data['diplome']);
            $moniteur->setSpecialite($data['specialite']);
            $moniteur->setUsername($data['username']);
            $moniteur->setRoles($data['roles']);
            $moniteur->setPassword($data['password']);
            $moniteur->setPrenomUser($data['prenomUser']);
            $moniteur->setAdresseUser($data['adresseUser']);
            $moniteur->setEmailUser($data['emailUser']);
            $moniteur->setStatutUser($data['statutUser']);

            $manager->persist($moniteur);
            $moniteurs[] = $moniteur;
        }

        // Création de Materiel
        $materielsData = [
            [
                'typeMateriel' => 'Vélo',
                'caracteristique' => 'Vélo de montagne',
                'numSerie' => '123456',
                'dateMiseEnService' => new DateTimeImmutable('2023-01-01'),
                'etatMateriel' => 'Bon état',
            ],
            // ... (autres matériels)
        ];

        $materiels = [];
        foreach ($materielsData as $data) {
            $materiel = new Materiel();
            $materiel->setTypeMateriel($data['typeMateriel']);
            $materiel->setCaracteristique($data['caracteristique']);
            $materiel->setNumSerie($data['numSerie']);
            $materiel->setDateMiseEnService($data['dateMiseEnService']);
            $materiel->setEtatMateriel($data['etatMateriel']);

            $manager->persist($materiel);
            $materiels[] = $materiel;
        }

        // Flush des entités sans dépendances
        $manager->flush();

        // Création de Paiements
        $paiementsData = [
            [
                'montant' => 150.00,
                'datePaiement' => new DateTimeImmutable('2023-10-01'),
                'modePaiement' => 'Carte bancaire',
                'statutPaiement' => 'Payé',
                'client' => $clients[0],
            ],
            // ... (autres paiements)
        ];

        $paiements = [];
        foreach ($paiementsData as $data) {
            $paiement = new Paiement();
            $paiement->setMontant($data['montant']);
            $paiement->setDatePaiement($data['datePaiement']);
            $paiement->setModePaiement($data['modePaiement']);
            $paiement->setStatutPaiement($data['statutPaiement']);
            $paiement->setClient($data['client']);

            $manager->persist($paiement);
            $paiements[] = $paiement;
        }

        // Flush des paiements pour obtenir les IDs
        $manager->flush();

        // Création de Factures
        $facturesData = [
            [
                'numFacture' => 'F12345',
                'montantTotal' => 150.00,
                'adresseEtablissement' => 'Camping Soleil, Route de la Plage, Marseille',
                'dateFacture' => new DateTimeImmutable(),
                'paiement' => $paiements[0], // Associer un paiement existant
            ],
            // ... (autres factures)
        ];

        $factures = [];
        foreach ($facturesData as $data) {
            $facture = new Facture();
            $facture->setNumFacture($data['numFacture']);
            $facture->setMontantTotal($data['montantTotal']);
            $facture->setAdresseEtablissement($data['adresseEtablissement']);
            $facture->setDateFacture($data['dateFacture']);
            $facture->setPaiement($data['paiement']);

            $manager->persist($facture);
            $factures[] = $facture;
        }

        // Flush des factures pour obtenir les IDs
        $manager->flush();

        // Création de Forfaits
        $forfaitsData = [
            [
                'nombreSeance' => 10,
                'dateDebut' => new DateTimeImmutable('2025-02-01'),
                'typeForfait' => 'Forfait Standard',
                'dateExpiration' => new DateTimeImmutable('2025-06-01'),
                'prixForfait' => 100.00,
                'prixRemiseForfait' => 90.00,
                'client' => $clients[0],
                'paiement' => $paiements[0], // Paiement existant pour ce forfait
            ],
            // ... (autres forfaits)
        ];

        $forfaits = [];
        foreach ($forfaitsData as $data) {
            $forfait = new Forfait();
            $forfait->setNombreSeance($data['nombreSeance']);
            $forfait->setDateDebut($data['dateDebut']);
            $forfait->setTypeForfait($data['typeForfait']);
            $forfait->setDateExpiration($data['dateExpiration']);
            $forfait->setPrixForfait($data['prixForfait']);
            $forfait->setPrixRemiseForfait($data['prixRemiseForfait']);
            $forfait->setClient($data['client']);
            $forfait->setPaiement($data['paiement']);

            $manager->persist($forfait);
            $forfaits[] = $forfait;
        }

        // Création de Cours
        $coursData = [
            [
                'dateCours' => new DateTimeImmutable('2025-02-01'),
                'heureDebutCours' => new DateTimeImmutable('2025-02-01 08:30:00'),
                'heureFinCours' => new DateTimeImmutable('2025-02-01 10:00:00'),
                'etatCours' => 'Disponible',
                'nombreDePlace' => 20,
                'moniteur' => $moniteurs[0],
                'proprietaire' => $proprietaires[0], // Associer un propriétaire existant
            ],
            // ... (autres cours)
        ];

        $cours = [];
        foreach ($coursData as $data) {
            $coursEntity = new Cours();
            $coursEntity->setDateCours($data['dateCours']);
            $coursEntity->setHeureDebutCours($data['heureDebutCours']);
            $coursEntity->setHeureFinCours($data['heureFinCours']);
            $coursEntity->setEtatCours($data['etatCours']);
            $coursEntity->setNombreDePlace($data['nombreDePlace']);
            $coursEntity->setMoniteur($data['moniteur']);
            $coursEntity->setProprietaire($data['proprietaire']);

            $manager->persist($coursEntity);
            $cours[] = $coursEntity;
        }

        // Flush des cours pour obtenir les IDs
        $manager->flush();

        // Création de Locations
        $locationsData = [
            [
                'dureeLocation' => new DateTimeImmutable('02:00:00'),
                'prixLocation' => 50.00,
                'prixLocationRemise' => 45.00,
                'dateLocation' => new DateTimeImmutable('2023-10-01'),
                'etatLocation' => 'En cours',
                'client' => $clients[0],
                'paiement' => $paiements[0],
                'materiel' => $materiels[0],
            ],
            // ... (autres locations)
        ];

        $locations = [];
        foreach ($locationsData as $data) {
            $location = new Location();
            $location->setDureeLocation($data['dureeLocation']);
            $location->setPrixLocation($data['prixLocation']);
            $location->setPrixLocationRemise($data['prixLocationRemise']);
            $location->setDateLocation($data['dateLocation']);
            $location->setEtatLocation($data['etatLocation']);
            $location->setClient($data['client']);
            $location->setPaiement($data['paiement']);
            $location->setMateriel($data['materiel']);

            $manager->persist($location);
            $locations[] = $location;
        }

        // Création de Pannes
        $pannesData = [
            [
                'description' => 'Problème de frein',
                'datePanne' => new DateTimeImmutable('2023-10-01'),
                'dateDebutReparation' => new DateTimeImmutable('2023-10-02'),
                'dateFinReparation' => new DateTimeImmutable('2023-10-05'),
                'etatPanne' => 'Réparé',
                'commentaire' => 'Frein réparé',
                'materiel' => $materiels[0],
            ],
            // ... (autres pannes)
        ];

        $pannes = [];
        foreach ($pannesData as $data) {
            $panne = new Panne();
            $panne->setDescription($data['description']);
            $panne->setDatePanne($data['datePanne']);
            $panne->setDateDebutReparation($data['dateDebutReparation']);
            $panne->setDateFinReparation($data['dateFinReparation']);
            $panne->setEtatPanne($data['etatPanne']);
            $panne->setCommentaire($data['commentaire']);
            $panne->setMateriel($data['materiel']);

            $manager->persist($panne);
            $pannes[] = $panne;
        }

        // Création de Participations
        $participationsData = [
            [
                'dateInscriptionCours' => new DateTimeImmutable('2023-10-01'),
                'statutParticipant' => 'Inscrit',
                'client' => $clients[0],
                'cours' => $cours[0],
            ],
            // ... (autres participations)
        ];

        $participations = [];
        foreach ($participationsData as $data) {
            $participation = new Participation();
            $participation->setDateInscriptionCours($data['dateInscriptionCours']);
            $participation->setStatutParticipant($data['statutParticipant']);
            $participation->setClient($data['client']);
            $participation->setCours($data['cours']);

            $manager->persist($participation);
            $participations[] = $participation;
        }

        // Création de Utilisations de Forfaits
        $utilisationForfaitsData = [
            [
                'dateUtilisation' => new DateTimeImmutable('2023-10-01'),
                'statutForfait' => 'Utilisé',
                'forfait' => $forfaits[0],
                'participation' => $participations[0],
            ],
            // ... (autres utilisations de forfaits)
        ];

        $utilisationForfaits = [];
        foreach ($utilisationForfaitsData as $data) {
            $utilisationForfait = new UtilisationForfait();
            $utilisationForfait->setDateUtilisation($data['dateUtilisation']);
            $utilisationForfait->setStatutForfait($data['statutForfait']);
            $utilisationForfait->setForfait($data['forfait']);
            $utilisationForfait->setParticipation($data['participation']);

            $manager->persist($utilisationForfait);
            $utilisationForfaits[] = $utilisationForfait;
        }

        // Création de Users
        $usersData = [
            [
                'username' => 'gestionnaire',
                'roles' => ['ROLE_GESTIONNAIRE'],
                'password' => '$2y$13$eImiTXuWVxfM37uY4JANjQ==', // Password hash for 'password'
                'prenomUser' => 'John',
                'adresseUser' => '123 Rue de la Paix',
                'emailUser' => 'john.doe@example.com',
                'statutUser' => 'Actif',
            ],
            // ... (autres users)
        ];

        $users = [];
        foreach ($usersData as $data) {
            $user = new UserClub();
            $user->setUsername($data['username']);
            $user->setRoles($data['roles']);
            $user->setPassword($data['password']);
            $user->setPrenomUser($data['prenomUser']);
            $user->setAdresseUser($data['adresseUser']);
            $user->setEmailUser($data['emailUser']);
            $user->setStatutUser($data['statutUser']);

            $manager->persist($user);
            $users[] = $user;
        }

        // Flush des entités persistées
        $manager->flush();
    }
}

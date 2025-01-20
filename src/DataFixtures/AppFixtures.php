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

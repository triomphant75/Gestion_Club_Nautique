<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Client;
use \DateTimeImmutable;

class ClientFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        // Données client 
        $clients = [
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'adresse' => '10 rue de Paris, Nancy',
                'email' => 'jean.dupont@example.com',
                'niveau' => 'Débutant',
                'telephone'=> '07 34 12 00 65',
                //'camping'=> 2,

            ],
            [
                'nom' => 'Durand',
                'prenom' => 'Marie',
                'adresse' => '5 avenue des Vosges, Nancy',
                'email' => 'marie.durand@example.com',
                'niveau' => 'Intermédiaire',
                'telephone'=> '06 33 89 10 12',

            ],
            [
                'nom' => 'Martin',
                'prenom' => 'Luc',
                'adresse' => '12 boulevard Saint-Georges, Nancy',
                'email' => 'luc.martin@example.com',
                'niveau' => 'Avancé',
                'telephone'=> '06 33 89 10 12',

            ],
            [
                'nom' => 'Bernard',
                'prenom' => 'Sophie',
                'adresse' => '20 place Stanislas, Nancy',
                'email' => 'sophie.bernard@example.com',
                'niveau' => 'Expert',
                'telephone'=> '06 33 89 10 12',

            ],
        ];
        
        foreach ($clients as $data) {
            $client = new Client();
            $client->setNomClient($data['nom']);
            $client->setPrenomClient($data['prenom']);
            $client->setAdresseClient($data['adresse']);
            $client->setDateInscriptionClient(new \DateTimeImmutable()); // Date actuelle
            $client->setEmailClient($data['email']);
            $client->setNiveauClient($data['niveau']);
            $client->setTelClient($data['telephone']);
            //$client->setCamping($data['camping']);


        
            $manager->persist($client);
        }
        
        $manager->flush();  
}
        
}

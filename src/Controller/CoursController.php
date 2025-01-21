<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Entity\Moniteur;
use App\Entity\UserClub;
use App\Form\AddClientToCoursType;
use App\Entity\Participation;
use Psr\Log\LoggerInterface;
use App\Form\UpdateParticipantStatusType;
use App\Entity\UtilisationForfait;

class CoursController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/cours', name: 'cours_index')]
    public function planifierCours(EntityManagerInterface $em, Request $request): Response
    {
        // Récupérer tous les cours
        $cours = $em->getRepository(Cours::class)->findAll();

        // Mettre à jour l'état des cours
        $now = new \DateTimeImmutable(); // Date et heure actuelles
        foreach ($cours as $coursItem) {
            $dateCours = $coursItem->getDateCours(); // Date du cours
            $heureDebut = $coursItem->getHeureDebutCours(); // Heure de début
            $heureFin = $coursItem->getHeureFinCours(); // Heure de fin

             // Combinez la date du cours avec les heures de début et de fin
            $debutCours = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $dateCours->format('Y-m-d') . ' ' . $heureDebut->format('H:i'));
            $finCours = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $dateCours->format('Y-m-d') . ' ' . $heureFin->format('H:i'));

            // Si la date et l'heure actuelles sont dans l'intervalle du cours
            if ($now >= $debutCours && $now <= $finCours) {
                $coursItem->setEtatCours('En cours');
            } elseif ($now > $finCours) {
                $coursItem->setEtatCours('Terminé');
            } elseif ($now < $debutCours) {
                $coursItem->setEtatCours('Planifié');
    }

            // Sauvegarder les modifications
            $em->persist($coursItem);
        }


        // Créer un nouvel objet Cours pour le formulaire
        $newCours = new Cours();

        // Récupérer tous les UserClub avec statut "actif"
        $userClubsActifs = $em->getRepository(UserClub::class)->findBy([
            'statutUser' => 'Disponible',
        ]);

        // Filtrer pour ne garder que les Moniteurs
        $moniteursActifs = array_filter($userClubsActifs, function ($userClub) {
            return in_array('ROLE_MONITEUR', $userClub->getRoles());
        });

        // Créer le formulaire et passer l'option 'moniteurs'
        $form = $this->createForm(CoursType::class, $newCours, [
            'moniteurs' => $moniteursActifs,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newCours);
            $em->flush();

            $this->addFlash('succes',"Cours ajouté avec succès.");


            return $this->redirectToRoute('cours_index');
        }

        // Préparer le formulaire d'ajout de client pour chaque cours
        $addClientForms = [];
        foreach ($cours as $coursItem) {
            // Créez un formulaire spécifique pour chaque cours
            $addClientForms[$coursItem->getId()] = $this->createForm(AddClientToCoursType::class, null, [
                'cours' => $coursItem,
            ])->createView();
        }

        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
            'addClientForms' => $addClientForms,
        ]);
    }

    #[Route('/cours/{id}', name: 'cours_show')]
    public function consulterCours(Cours $cours): Response
    {
        return $this->render('cours/show.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/cours/{id}/participants', name: 'cours_participants')]
    public function Consulterparticipants(Cours $cours, EntityManagerInterface $em): Response
    {
        // Récupérer les participations associées au cours
        $participants = $em->getRepository(Participation::class)->findBy(['cours' => $cours]);

        // Créer un formulaire vide pour la mise à jour du statut du participant
        $form = $this->createForm(UpdateParticipantStatusType::class);

        return $this->render('cours/participants.html.twig', [
            'cours' => $cours,
            'participants' => $participants,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cours/{id}/add-clients', name: 'cours_add_clients')]
    public function inscrireClientCours(
        Cours $cours,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        // Créer le formulaire
        $form = $this->createForm(AddClientToCoursType::class, null, [
            'cours' => $cours,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les clients sélectionnés
            $clients = $form->get('client')->getData();
            $dateInscription = $form->get('dateInscriptionCours')->getData();
            $statut = $form->get('statutParticipant')->getData();

            try {
                // Vérifier si $clients est un tableau ou un seul objet
                if (is_array($clients) || $clients instanceof \Traversable) {
                    foreach ($clients as $client) {
                        // Vérifier si le client est déjà inscrit à ce cours
                        $existingParticipation = $em->getRepository(Participation::class)->findOneBy([
                            'cours' => $cours,
                            'client' => $client,
                        ]);

                        // Si le client n'est pas encore inscrit, on crée une nouvelle participation
                        if (!$existingParticipation) {
                            $participation = new Participation();
                            $participation->setCours($cours);
                            $participation->setClient($client);
                            $participation->setDateInscriptionCours($dateInscription);
                            $participation->setStatutParticipant($statut);
                            // Vérifier l'objet Participation avant de persister

                            $em->persist($participation);

                            // Mettre à jour l'utilisation du forfait
                            $forfait = $client->getForfait();
                            if ($forfait) {
                                $seancesRestantes = $forfait->getSeancesRestantes();
                                if ($seancesRestantes > 0) {
                                    $forfait->setSeancesRestantes($seancesRestantes - 1);

                                    if ($seancesRestantes - 1 == 0) {
                                        $forfait->setStatutForfait('Terminé');
                                    } elseif ($seancesRestantes - 1 > 0) {
                                        $forfait->setStatutForfait('En cours');
                                    }

                                    $utilisationForfait = new UtilisationForfait();
                                    $utilisationForfait->setDateUtilisation(new \DateTimeImmutable());
                                    $utilisationForfait->setStatutForfait($forfait->getStatutForfait());
                                    $utilisationForfait->setForfait($forfait);
                                    $utilisationForfait->setParticipation($participation);

                                    $em->persist($utilisationForfait);
                                } else {
                                    $this->addFlash('warning', 'Aucune séance restante pour ce forfait.');
                                }
                            } else {
                                $this->addFlash('error', 'Forfait introuvable pour ce client.');
                            }
                        } else {
                            $this->addFlash('warning', 'Client déjà inscrit à ce cours.');
                        }
                    }
                } else {
                    // Vérifier si le client est déjà inscrit à ce cours
                    $existingParticipation = $em->getRepository(Participation::class)->findOneBy([
                        'cours' => $cours,
                        'client' => $clients,
                    ]);

                    // Si le client n'est pas encore inscrit, on crée une nouvelle participation
                    if (!$existingParticipation) {
                        $participation = new Participation();
                        $participation->setCours($cours);
                        $participation->setClient($clients);
                        $participation->setDateInscriptionCours($dateInscription);
                        $participation->setStatutParticipant($statut);
                        // Vérifier l'objet Participation avant de persister

                        $em->persist($participation);

                        // Mettre à jour l'utilisation du forfait
                        $forfait = $clients->getForfaits()->first();
                        if ($forfait->getSeancesRestantes() > 0) {
                            $utilisationForfait = new UtilisationForfait();
                            $utilisationForfait->setDateUtilisation(new \DateTimeImmutable());
                            $utilisationForfait->setStatutForfait(
                                $forfait->getSeancesRestantes() - 1 > 0 ? 'En cours' : 'Terminé'
                            );
                            $utilisationForfait->setForfait($forfait);
                            $utilisationForfait->setParticipation($participation);
                        
                            $em->persist($utilisationForfait);
                            $em->flush();
                        
                            $this->addFlash('success', 'Forfait utilisé avec succès.');
                        } else {
                            $this->addFlash('warning', 'Aucune séance restante pour ce forfait.');
                        }
                        
                    } else {
                        $this->addFlash('warning', 'Client déjà inscrit à ce cours.');
                    }
                }

                $em->flush();
                $this->addFlash('success', 'Clients ajoutés au cours avec succès.');

            } catch (\Doctrine\DBAL\Exception $e) {
                // Récupérer le message de l'exception
                $errorMessage = $e->getPrevious()->getMessage();

                // Extraire uniquement le message personnalisé du trigger
                if (preg_match("/ERREUR: (.+)/", $errorMessage, $matches)) {
                    $cleanMessage = $matches[1];
                } else {
                    $cleanMessage = 'Une erreur est survenue.';
                }

                // Ajouter le message filtré comme un flash
                $this->addFlash('error', $cleanMessage);
            }
            // Message de succès et redirection
            return $this->redirectToRoute('cours_participants', ['id' => $cours->getId()]);
        }

        // Retourner toujours une réponse, même si le formulaire n'est pas soumis ou n'est pas valide
        return $this->render('cours/add_clients.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cours/{id}/participant/{participantId}/update-status', name: 'cours_participant_update_status', methods: ['POST'])]
public function miseajourStatutClient(
    int $participantId,
    Request $request,
    EntityManagerInterface $em
): Response {
    // Rechercher explicitement l'entité Participation
    $participation = $em->getRepository(Participation::class)->find($participantId);

    if (!$participation) {
        throw $this->createNotFoundException('Participation introuvable.');
    }

    // Récupère le nouveau statut depuis le formulaire
    $newStatus = $request->request->get('statutParticipant');

    if (!$newStatus) {
        $this->addFlash('error', 'Statut invalide.');
        return $this->redirectToRoute('cours_participants', ['id' => $participation->getCours()->getId()]);
    }

    $participation->setStatutParticipant($newStatus);
    $em->flush();

    $this->addFlash('success', 'Statut du participant mis à jour avec succès.');
    return $this->redirectToRoute('cours_participants', ['id' => $participation->getCours()->getId()]);
}

    
    


}

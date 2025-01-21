<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\UtilisationForfait;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Participation;

class UtilisationForfaitController extends AbstractController
{  private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/utilisation-forfaits', name: 'utilisation_forfaits_index')]
    public function index(): Response
    {
        $utilisations = $this->entityManager->getRepository(UtilisationForfait::class)->findAll();

        return $this->render('utilisation_forfait/index.html.twig', [
            'utilisations' => $utilisations,
        ]);
    }

    #[Route('/utilisation-forfait/{id}/decrement', name: 'utilisation_forfait_decrement', methods: ['POST'])]
    public function decrementForfait(Participation $participation): Response
    {
        $forfait = $participation->getClient()->getForfaits()->first();

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

                $this->entityManager->persist($utilisationForfait);
                $this->entityManager->flush();

                $this->addFlash('success', 'Forfait décrémenté avec succès.');
            } else {
                $this->addFlash('warning', 'Aucune séance restante pour ce forfait.');
            }
        } else {
            $this->addFlash('error', 'Forfait introuvable pour ce client.');
        }

        return $this->redirectToRoute('cours_participants', ['id' => $participation->getCours()->getId()]);
    }

}

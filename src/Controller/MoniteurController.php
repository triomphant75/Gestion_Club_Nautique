<?php
// src/Controller/MoniteurController.php
namespace App\Controller;

use App\Entity\Moniteur;
use App\Form\AddMoniteurType; // Ensure this class exists in the specified namespace
use App\Form\MoniteurType;
use App\Repository\MoniteurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

class MoniteurController extends AbstractController
{
    #[Route('/moniteur', name: 'moniteur_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function creerMoniteur(
        ManagerRegistry $doctrine,
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $repository = $doctrine->getRepository(Moniteur::class);
        $moniteurs = $repository->findAll();

        $moniteur = new Moniteur();
        $addForm = $this->createForm(AddMoniteurType::class, $moniteur);

        $addForm->handleRequest($request);
        if ($addForm->isSubmitted() && $addForm->isValid()) {
            // Encode password and set default roles/statut
            $password = $passwordHasher->hashPassword($moniteur, $moniteur->getPassword());
            $moniteur->setPassword($password);
            $moniteur->setRoles(['ROLE_MONITEUR']);
            $moniteur->setStatutUser('Disponible');

            $entityManager = $doctrine->getManager();
            $entityManager->persist($moniteur);
            $entityManager->flush();

            $this->addFlash('success', 'Moniteur ajouté avec succès.');
            return $this->redirectToRoute('moniteur_index');
        }

        $editForm = $this->createForm(MoniteurType::class);

        return $this->render('moniteur/index.html.twig', [
            'moniteurs' => $moniteurs,
            'add_form' => $addForm->createView(),
            'edit_form' => $editForm->createView(),
        ]);
    }

    #[Route('/moniteur/edit/{id}', name: 'edit_moniteur', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function ModifierMoniteur(
        Request $request,
        Moniteur $moniteur,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$moniteur) {
            throw $this->createNotFoundException('Moniteur introuvable.');
        }

        $form = $this->createForm(MoniteurType::class, $moniteur, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Moniteur modifié avec succès.');
            return $this->redirectToRoute('moniteur_index');
        }

        return $this->render('moniteur/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/moniteur/delete/{id}', name: 'moniteur_delete', methods: ['POST'])]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function supprimerMoniteur(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $moniteur = $doctrine->getRepository(Moniteur::class)->find($id);

        if (!$moniteur) {
            throw $this->createNotFoundException('Moniteur non trouvé.');
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($moniteur);
        $entityManager->flush();

        $this->addFlash('success', 'Moniteur supprimé avec succès.');
        return $this->redirectToRoute('moniteur_index');
    }

    #[Route('/moniteur/{id}/changeStatut', name: 'change_statut', methods: ['POST'])]
    public function changeStatut(
        Moniteur $moniteur,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$moniteur) {
            throw $this->createNotFoundException('Moniteur introuvable.');
        }

        $newStatut = $moniteur->getStatutUser() === 'Disponible' ? 'Indisponible' : 'Disponible';
        $moniteur->setStatutUser($newStatut);

        $entityManager->flush();

        $this->addFlash('success', 'Statut du moniteur modifié avec succès.');
        return $this->redirectToRoute('moniteur_index');
    }

}
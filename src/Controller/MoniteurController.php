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
use Doctrine\ORM\EntityManagerInterface;

class MoniteurController extends AbstractController
{
    #[Route('/moniteur', name: 'moniteur_index')]
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
{
    $repository = $doctrine->getRepository(Moniteur::class);
    $moniteurs = $repository->findAll();

    $moniteur = new Moniteur();
    $addForm = $this->createForm(AddMoniteurType::class, $moniteur);

    $addForm->handleRequest($request);
    if ($addForm->isSubmitted() && $addForm->isValid()) {
        // Encoder le mot de passe
        $password = $passwordHasher->hashPassword($moniteur, $moniteur->getPassword());
        $moniteur->setPassword($password);

        $moniteur->setRoles(['ROLE_MONITEUR']);
        $moniteur->setStatutUser('Disponible');

        $entityManager = $doctrine->getManager();
        $entityManager->persist($moniteur);
        $entityManager->flush();

        return $this->redirectToRoute('moniteur_index');
    }

    // Ajouter un formulaire d'édition vide
    $editForm = $this->createForm(MoniteurType::class);

    return $this->render('moniteur/index.html.twig', [
        'moniteurs' => $moniteurs,
        'add_form' => $addForm->createView(),
        'edit_form' => $editForm->createView(), // Passez ce formulaire à la vue
    ]);
}


    #[Route('/moniteur/edit/{id}', name: 'edit_moniteur')]
    public function edit(Request $request, Moniteur $moniteur, MoniteurRepository $moniteurRepository): Response
    {
        $form = $this->createForm(MoniteurType::class, $moniteur, [
            'is_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer la modification du statut
            $moniteurRepository->save($moniteur, true);

            $this->addFlash('success', 'Moniteur modifié avec succès.');

            return $this->redirectToRoute('moniteur_list');
        }

        return $this->render('moniteur/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    

    #[Route('/moniteur/delete/{id}', name: 'moniteur_delete')]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $moniteur = $doctrine->getRepository(Moniteur::class)->find($id);

        if (!$moniteur) {
            throw $this->createNotFoundException('Moniteur non trouvé.');
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($moniteur);
        $entityManager->flush();

        return $this->redirectToRoute('moniteur_index');
    }

    #[Route('/moniteur/{id}/changeStatut', name:'change_statut')]
    public function changeStatut(Moniteur $moniteur, EntityManagerInterface $em): Response
    {
        // Change le statut en fonction de l'état actuel
        if ($moniteur->getStatutUser() === 'Disponible') {
            $moniteur->setStatutUser('Indisponible');
        } else {
            $moniteur->setStatutUser('Disponible');
        }

        // Sauvegarder les changements dans la base de données
        $em->persist($moniteur);
        $em->flush();

        // Redirection après la modification
        return $this->redirectToRoute('moniteur_index');
    }
}
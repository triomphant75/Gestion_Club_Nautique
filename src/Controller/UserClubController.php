<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserClub;
use App\Form\UserClubType;

class UserClubController extends AbstractController
{#[Route('/user/add', name: 'user_add')]
    public function creerUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new UserClub();
        $form = $this->createForm(UserClubType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);
            $user->setPassword($hashedPassword);

            // Set the roles based on the selected roles
            $user->setRoles($form->get('roles')->getData());

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user_club/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/list', name: 'user_list')]
    public function consulterListeUser(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(UserClub::class)->findAll();

        // Filter users by role
        $filteredUsers = array_filter($users, function($user) {
            return in_array('ROLE_PROPRIETAIRE', $user->getRoles()) ||
                   in_array('ROLE_COMPAGNE_PROPRIETAIRE', $user->getRoles()) ||
                   in_array('ROLE_GESTIONNAIRE', $user->getRoles());
        });

        return $this->render('user_club/list.html.twig', [
            'users' => $filteredUsers,
        ]);
    }
}

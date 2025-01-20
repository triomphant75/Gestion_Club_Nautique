<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CampingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Camping;
use App\Form\CampingType;

class CampingController extends AbstractController
{
    #[Route('/campings', name: 'camping_list')]
    public function list(CampingRepository $campingRepository): Response
    {
        $campings = $campingRepository->findAll();

        return $this->render('camping/list.html.twig', [
            'campings' => $campings,
        ]);
    }

    #[Route('/camping/new', name: 'camping_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $camping = new Camping();
        $form = $this->createForm(CampingType::class, $camping);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($camping);
            $entityManager->flush();

            return $this->redirectToRoute('camping_list');
        }

        return $this->render('camping/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExceptionController extends AbstractController
{
    public function showAccessDenied(): Response
    {
        return $this->render('exception/access_denied.html.twig');
    }

    public function showNotFound(): Response
    {
        return $this->render('exception/not_found.html.twig');
    }
}

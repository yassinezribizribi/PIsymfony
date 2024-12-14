<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ParticipationfrontController extends AbstractController
{
    #[Route('/participationfront', name: 'app_participationfront')]
    public function index(): Response
    {
        return $this->render('participationfront/index.html.twig', [
            'controller_name' => 'ParticipationfrontController',
        ]);
    }
}

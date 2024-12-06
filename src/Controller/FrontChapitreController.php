<?php
// src/Controller/FrontChapitreController.php

namespace App\Controller;

use App\Entity\Chapitre;
use App\Repository\ChapitreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontChapitreController extends AbstractController
{
    #[Route('/front/chapitres', name: 'front_chapitre_index')]
    public function index(ChapitreRepository $chapitreRepository): Response
    {
        // Récupérer tous les chapitres
        $chapitres = $chapitreRepository->findAll();

        return $this->render('front_chapitre/index.html.twig', [
            'chapitres' => $chapitres,
        ]);
    }

    #[Route('/front/chapitre/{id}', name: 'front_chapitre_show')]
    public function show(Chapitre $chapitre): Response
    {
        // Afficher un chapitre spécifique
        return $this->render('front_chapitre/show.html.twig', [
            'chapitre' => $chapitre,
        ]);
    }
}

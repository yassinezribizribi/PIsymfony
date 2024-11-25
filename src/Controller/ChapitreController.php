<?php

namespace App\Controller;

use App\Entity\Chapitre;
use App\Form\ChapitreType;
use App\Repository\ChapitreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chapitre')]
final class ChapitreController extends AbstractController{
    #[Route(name: 'app_chapitre_index', methods: ['GET'])]
    public function index(ChapitreRepository $chapitreRepository): Response
    {
        return $this->render('chapitre/index.html.twig', [
            'chapitres' => $chapitreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chapitre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chapitre = new Chapitre();
        $form = $this->createForm(ChapitreType::class, $chapitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chapitre);
            $entityManager->flush();

            return $this->redirectToRoute('app_chapitre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chapitre/new.html.twig', [
            'chapitre' => $chapitre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chapitre_show', methods: ['GET'])]
    public function show(Chapitre $chapitre): Response
    {
        return $this->render('chapitre/show.html.twig', [
            'chapitre' => $chapitre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chapitre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chapitre $chapitre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChapitreType::class, $chapitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chapitre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chapitre/edit.html.twig', [
            'chapitre' => $chapitre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chapitre_delete', methods: ['POST'])]
    public function delete(Request $request, Chapitre $chapitre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chapitre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chapitre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chapitre_index', [], Response::HTTP_SEE_OTHER);
    }
}

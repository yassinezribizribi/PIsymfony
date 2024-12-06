<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/cours')]
final class CoursController extends AbstractController
{
    private const UPLOADS_DIRECTORY = __DIR__ . '/../../public/uploads/cours';

    #[Route(name: 'app_cours_index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->findAll();
        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
        ]);
    }

    // Dans CoursController.php
#[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $cour = new Cours();
    $form = $this->createForm(CoursType::class, $cour);
    $form->handleRequest($request);
    

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $file */
        $file = $form->get('contenu')->getData();
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();
        $typeContenu = $form->get('typeContenu')->getData();

        // Gérer le fichier image
        if ($imageFile) {
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            try {
                $imageFile->move(self::UPLOADS_DIRECTORY . '/images', $newFilename);
                $cour->setImage($newFilename);
            } catch (FileException $e) {
                throw new \Exception("Erreur lors de l'upload de l'image : " . $e->getMessage());
            }
        }

        // Gérer le contenu (PDF ou vidéo)
        if ($file) {
            $newFilename = uniqid() . '.' . $file->guessExtension();
            try {
                $file->move(self::UPLOADS_DIRECTORY, $newFilename);
                $cour->setContenu($newFilename);
            } catch (FileException $e) {
                throw new \Exception("Erreur lors de l'upload du fichier : " . $e->getMessage());
            }
        }

        $cour->setTypeContenu($typeContenu);

        $entityManager->persist($cour);
        $entityManager->flush();

        return $this->redirectToRoute('app_cours_index');
    }

    return $this->render('cours/new.html.twig', [
        'cour' => $cour,
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cour): Response
    {
        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(CoursType::class, $cour);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $file */
        $file = $form->get('contenu')->getData();
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();
        $typeContenu = $form->get('typeContenu')->getData();

        // Gérer le fichier image
        if ($imageFile) {
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            try {
                $imageFile->move(self::UPLOADS_DIRECTORY . '/images', $newFilename);
                $cour->setImage($newFilename);
            } catch (FileException $e) {
                throw new \Exception("Erreur lors de l'upload de l'image : " . $e->getMessage());
            }
        }

        // Gérer le contenu (PDF ou vidéo)
        if ($file) {
            $newFilename = uniqid() . '.' . $file->guessExtension();
            try {
                $file->move(self::UPLOADS_DIRECTORY, $newFilename);
                $cour->setContenu($newFilename);
            } catch (FileException $e) {
                throw new \Exception("Erreur lors de l'upload du fichier : " . $e->getMessage());
            }
        }

        $cour->setTypeContenu($typeContenu);

        $entityManager->flush();

        return $this->redirectToRoute('app_cours_index');
    }

    return $this->render('cours/edit.html.twig', [
        'cours' => $cour,
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cour->getId(), $request->request->get('_token'))) {
            $filePath = self::UPLOADS_DIRECTORY . '/' . $cour->getContenu();
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $entityManager->remove($cour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cours_index');
    }
    
}

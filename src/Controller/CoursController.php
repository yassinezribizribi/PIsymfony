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
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/ad/cours')]
final class CoursController extends AbstractController
{
    #[Route(name: 'app_cours_index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }

    // src/Controller/CoursController.php

    #[Route('/cours/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cours): Response
    {
        // Ici, Symfony tente de récupérer l'objet `Cours` par son ID
        return $this->render('cours/show.html.twig', [
            'cours' => $cours,
        ]);
    }


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

            // Validation et upload de l'image
            if ($imageFile) {
                $imageFilename = uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move($this->getParameter('images_directory'), $imageFilename);
                    $cour->setImage($imageFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', "Erreur lors de l'upload de l'image : " . $e->getMessage());
                }
            }

            // Validation et upload du contenu
            if ($file) {
                $fileExtension = $file->guessExtension();
                if (($typeContenu === 'PDF' && $fileExtension !== 'pdf') ||
                    ($typeContenu === 'Vidéo' && !in_array($fileExtension, ['mp4', 'avi', 'mkv']))) {
                    $this->addFlash('error', "Le fichier téléchargé ne correspond pas au type de contenu sélectionné.");
                    return $this->redirectToRoute('app_cours_new');
                }

                $fileFilename = uniqid() . '.' . $fileExtension;
                try {
                    $file->move($this->getParameter('uploads_directory'), $fileFilename);
                    $cour->setContenu($fileFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', "Erreur lors de l'upload du fichier : " . $e->getMessage());
                }
            }

            $entityManager->persist($cour);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
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

            if ($imageFile) {
                if ($cour->getImage() && file_exists($this->getParameter('images_directory') . '/' . $cour->getImage())) {
                    unlink($this->getParameter('images_directory') . '/' . $cour->getImage());
                }

                $imageFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('images_directory'), $imageFilename);
                $cour->setImage($imageFilename);
            }

            if ($file) {
                if ($cour->getContenu() && file_exists($this->getParameter('uploads_directory') . '/' . $cour->getContenu())) {
                    unlink($this->getParameter('uploads_directory') . '/' . $cour->getContenu());
                }

                $fileFilename = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('uploads_directory'), $fileFilename);
                $cour->setContenu($fileFilename);
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_cours_index');
        }

        return $this->render('cours/edit.html.twig', [
            'cours' => $cour,
            'form' => $form->createView(),
        ]);
    }

    // src/Controller/CoursController.php

#[Route('/{id}/delete', name: 'app_cours_delete', methods: ['POST'])]
public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
{
    // Vérification du token CSRF pour éviter les attaques CSRF
    if ($this->isCsrfTokenValid('delete' . $cour->getId(), $request->request->get('_token'))) {
        // Supprimer l'image et le contenu si existants
        if ($cour->getImage() && file_exists($this->getParameter('images_directory') . '/' . $cour->getImage())) {
            unlink($this->getParameter('images_directory') . '/' . $cour->getImage());
        }

        if ($cour->getContenu() && file_exists($this->getParameter('uploads_directory') . '/' . $cour->getContenu())) {
            unlink($this->getParameter('uploads_directory') . '/' . $cour->getContenu());
        }

        // Supprimer le cours de la base de données
        $entityManager->remove($cour);
        $entityManager->flush();

        $this->addFlash('success', 'Le cours a été supprimé avec succès.');
    } else {
        $this->addFlash('error', 'Erreur lors de la suppression du cours.');
    }

    return $this->redirectToRoute('app_cours_index');
}


    
}

<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/evenement')]
final class EvenementController extends AbstractController
{
    private const UPLOADS_DIRECTORY = __DIR__ . '/../../public/uploads/cours';

    #[Route(name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }        

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageevenement')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                try {
                    // Déplacez l'image dans le dossier public/uploads/images/
                    $imageFile->move(
                        $this->getParameter('upload_image_directory'),  // Assurez-vous que ce paramètre est configuré dans services.yaml
                        $newFilename
                    );
                    $evenement->setImageEvenement($newFilename); // Enregistrez le nom de l'image dans la base de données
                } catch (FileException $e) {
                    throw new \Exception("Erreur lors de l'upload de l'image : " . $e->getMessage());
                }
            }

            // Gestion de la vidéo
            /** @var UploadedFile $videoFile */
            $videoFile = $form->get('videoevenement')->getData();
            if ($videoFile) {
                $newFilename = uniqid() . '.' . $videoFile->guessExtension();
                try {
                    // Déplacez la vidéo dans le dossier public/uploads/videos/
                    $videoFile->move(
                        $this->getParameter('upload_video_directory'), // Assurez-vous que ce paramètre est configuré dans services.yaml
                        $newFilename
                    );
                    $evenement->setVideoEvenement($newFilename); // Enregistrez le nom de la vidéo dans la base de données
                } catch (FileException $e) {
                    throw new \Exception("Erreur lors de l'upload de la vidéo : " . $e->getMessage());
                }
            }

            // Prix (géré via le setter `setPrixEvenement`)
            $evenement->setPrixEvenement($form->get('prixevenement')->getData());

            // Persistance de l'entité
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Evenement $evenement,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $imageFile = $form->get('imageevenement')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Erreur lors du téléchargement de l\'image.');
                }

                $evenement->setImageEvenement($newFilename); // Assurez-vous que cette propriété existe dans l'entité
            }

            // Gestion de la vidéo
            $videoFile = $form->get('videoevenement')->getData();
            if ($videoFile) {
                $originalFilename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $videoFile->guessExtension();

                try {
                    $videoFile->move(
                        $this->getParameter('videos_directory'),
                        $newFilename
                    );
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Erreur lors du téléchargement de la vidéo.');
                }

                $evenement->setVideoEvenement($newFilename); // Assurez-vous que cette propriété existe dans l'entité
            }

            $entityManager->persist($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'L\'événement a été mis à jour avec succès.');

            return $this->redirectToRoute('app_evenement_index');
        }

        return $this->render('evenement/edit.html.twig', [
            'form' => $form->createView(),
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
    
            $this->addFlash('success', 'L\'événement a été supprimé avec succès.');
        }
    
        return $this->redirectToRoute('app_evenement_index');
    }
}
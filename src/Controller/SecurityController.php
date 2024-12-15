<?php
// SecurityController.php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cours;
use App\Entity\Utilisateur;
use App\Repository\EvenementRepository;
use App\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RegistrationFormType;

class SecurityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Redirige l'utilisateur vers le profil approprié selon son rôle.
     */
    public function redirectToProfile(): Response
    {
        if ($this->isGranted('ROLE_TEACHER')) {
            return $this->redirectToRoute('teacher_profile');
        } elseif ($this->isGranted('ROLE_STUDENT')) {
            return $this->redirectToRoute('student_profile');
        }

        // Si aucun rôle spécifique, redirige vers une page d'erreur ou générique
        return $this->redirectToRoute('app_home');
    }

    /**
     * Affiche le profil de l'enseignant.
     */
    public function teacherProfile(): Response
    {
        // Utilisation de l'EntityManager pour récupérer les cours
        $courses = $this->entityManager
            ->getRepository(Cours::class)
            ->findAll();

        $students = $this->entityManager
            ->getRepository(Utilisateur::class)
            ->findByRole('ROLE_STUDENT');

        // Passer les cours et étudiants à la vue
        return $this->render('back/gestionuser/profile1.html.twig', [
            'courses' => $courses,
            'students' => $students,
        ]);
    }

    /**
     * Affiche le profil de l'étudiant avec ses événements associés.
     */
    public function studentProfile(Request $request, EvenementRepository $evenementRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $utilisateur = $this->getUser();

        // Vérifier que l'utilisateur est récupéré
        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Créer un formulaire d'édition du profil
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);

        // Traiter le formulaire (si soumis)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications de l'utilisateur
            $entityManager->flush();

            // Ajouter un message flash pour informer l'utilisateur
            $this->addFlash('success', 'Profil mis à jour avec succès!');

            // Rediriger vers la même page pour recharger les informations
            return $this->redirectToRoute('student_profile');
        }

        // Charger uniquement les événements associés à l'étudiant
        $evenements = $evenementRepository->findBy([ 'utilisateur' => $utilisateur ]);

        // Rendu de la vue avec l'utilisateur et les événements
        return $this->render('back/gestionuser/profile.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
            'evenements' => $evenements, // Ajouter les événements à la vue
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut rester vide car Symfony gère automatiquement la déconnexion
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

<?php
// SecurityController.php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cours;
use App\Entity\Utilisateur;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        // Passer les cours à la vue
        return $this->render('back/gestionuser/profile1.html.twig', [
            'courses' => $courses,
            'students' => $students,

        ]);
    }

    /**
     * Affiche le profil de l'étudiant.
     */
    public function studentProfile(): Response
    {
        return $this->render('back/gestionuser/profile.html.twig'); // Vue pour étudiants
    }



    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut rester vide car Symfony gère automatiquement la déconnexion
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

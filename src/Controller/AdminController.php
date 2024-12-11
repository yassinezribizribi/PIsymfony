<?php
// src/Controller/AdminController.php

namespace App\Controller;
use App\Entity\Utilisateur; // Assurez-vous d'importer l'entité correcte

use Symfony\Component\HttpFoundation\Request; // Assurez-vous que cette ligne est présente
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;  // Assurez-vous d'importer le bon repository
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\Security;

class AdminController extends AbstractController
{
    private $security;
    private $utilisateurRepository;
    private $entityManager;


    public function __construct(Security $security, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(UtilisateurRepository $userRepository): Response
    {
        $totalUsers = $userRepository->count([]);  // Ici, nous comptons tous les utilisateurs

        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }
        $totalUsers = $userRepository->count([]); // Total des utilisateurs

        return $this->render('admin/dashboard.html.twig', [
            'totalUsers' => $totalUsers,
        ]);
    }

    #[Route('/admin/utilisateurs', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(Request $request, UtilisateurRepository $utilisateurRepository): Response

    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }
        $role = $request->query->get('role');
        $utilisateurs = $role ? $utilisateurRepository->findByRole($role) : $utilisateurRepository->findAll();
        

        return $this->render('admin/user_list.html.twig', [
            'utilisateurs' => $utilisateurs
        ]);
    }
/**
     * @Route("/user/{id}", name="user_show")
     */
    public function showUser($id): Response
    {
        // Utilisation de l'EntityManager pour récupérer l'utilisateur
        $user = $this->entityManager->getRepository(Utilisateur::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        return $this->render('admin/user_show.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/user/{id}/edit", name="user_edit")
     */
    public function edit($id, Request $request, EntityManagerInterface $entityManager)
    {
        // Récupérer l'utilisateur par son ID
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);

        // Si l'utilisateur n'existe pas, rediriger vers la liste des utilisateurs
        if (!$user) {
            $this->addFlash('error', 'Utilisateur introuvable');
            return $this->redirectToRoute('user_list'); // 'user_list' est le nom de la route pour la liste des utilisateurs
        }

        // Créer le formulaire de modification
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Traiter la requête HTTP (s'il y a une soumission de formulaire)
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Afficher un message de succès
            $this->addFlash('success', 'Utilisateur modifié avec succès');

            // Rediriger vers la page de détails de l'utilisateur ou la liste des utilisateurs
            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        // Afficher la vue avec le formulaire
        return $this->render('admin/user_edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

   /**
     * @Route("/user/{id}/delete", name="user_delete")
     */
    public function delete($id): Response
    {
        // Utilisation de l'EntityManager pour accéder au repository
        $user = $this->entityManager->getRepository(Utilisateur::class)->find($id);
        
        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }

        // Redirection ou autre logique après la suppression
        return $this->redirectToRoute('user_list');
    }
   

}

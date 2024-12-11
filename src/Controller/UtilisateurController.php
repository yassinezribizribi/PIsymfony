<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Form\AdminType;
use App\Form\ChangePasswordType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use DateTime;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;





#[Route('/utilisateur')]
final class UtilisateurController extends AbstractController{
    private $tokenStorage;
    private $requestStack;
    private $security;
    private $entityManager;  // Déclaration de la propriété $entityManager
    // Déclaration de la propriété $security


    public function __construct(TokenStorageInterface $tokenStorage, RequestStack $requestStack,Security $security, EntityManagerInterface $entityManager)
{
    $this->tokenStorage = $tokenStorage;
    $this->requestStack = $requestStack;
    $this->security = $security; // Initialisation de la propriété $security
    $this->entityManager = $entityManager; 

}
    

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]  // Seul un administrateur peut modifier un utilisateur

    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/utilisateur/delete/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(int $id, Request $request)
    {
        // Retrieve the entity
        $utilisateur = $this->entityManager->getRepository(Utilisateur::class)->find($id);

        // Check if the entity exists
        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur not found.');
        }

        // Validate the CSRF token
        if (!$this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        // Remove the entity from the database
        $this->entityManager->remove($utilisateur);
        $this->entityManager->flush();

        // Redirect to a success page or list of users
        return $this->redirectToRoute('app_utilisateur_list');
    }


    

    
   /**
     * @Route("/profile", name="app_profile")
     */
    public function profile(Request $request): Response
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser) {
            throw $this->createAccessDeniedException('You must be logged in to access your profile.');
        }

        // Vérification du rôle admin
        if (in_array('ROLE_ADMIN', $currentUser->getRoles())) {
            return $this->redirectToRoute('admin_dashboard'); // Rediriger vers le dashboard admin si l'utilisateur a le rôle ROLE_ADMIN
        }

        // Sinon, afficher le profil utilisateur classique
        $utilisateur = $this->entityManager->getRepository(Utilisateur::class)->find($currentUser->getId());

        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('back/gestionuser/profile.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }
    

    /**
     * @Route("/forgot-password", name="app_dashForget")
     */
    public function forgotPassword()
    {
        // Logique de la page Mot de Passe Oublié
        return $this->render('front/gestionuser/forgot_password.html.twig');
    }
}

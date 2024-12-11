<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route(path:'/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $errorC = $request->query->get('errorC');

        return $this->render('front/gestionuser/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'captcha' => $errorC,
        ]);
    }

    #[Route('/dashLog', name: 'app_dashLog')]
    public function dashLog(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $errorC = $request->query->get('errorC');

        return $this->render('back/gestionuser/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'captcha' => $errorC,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/logoutd', name: 'app_logoutd')]
    public function logoutd(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/loginmo', name: 'app_loginmo')]
    public function loginmo(Request $request, UtilisateurRepository $repo): JsonResponse
    {
        // Get the username and password input from the user
        $username = $request->get('email');
        $password = $request->get('password');

        // Authenticate the user
        $user = $repo->findOneBy(['email' => $username]);
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse('Invalid', Response::HTTP_UNAUTHORIZED);
        }


        // Return the JWT in the response body
        return new JsonResponse($user->getId());
    }

    #[Route('/forget-password', name: 'app_forget')]
    public function forgetPassword(): Response
    {
    return $this->render('front/gestionuser/forget_password.html.twig');
    }

}

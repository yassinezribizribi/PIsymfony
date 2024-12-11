<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\FormLoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegistrationController extends AbstractController
{
    #[Route('/register-choice', name: 'app_register_choice')]
    public function registerChoice(): Response
    {
        return $this->render('registration/choice.html.twig');
    }
    #[Route('/register-student', name: 'app_register_student')]
    #[Route('/register-student', name: 'app_register_student')]
public function registerStudent(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
{
    $user = new Utilisateur();

    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_STUDENT']);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    return $this->render('front/gestionuser/signup.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}

#[Route('/register-teacher', name: 'app_register_teacher')]
public function registerTeacher(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
{
    $user = new Utilisateur();

    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_TEACHER']);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    return $this->render('front/gestionuser/teachersignup.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}

    #[Route(path:'/SignupUserJson', name: 'app_SignupUserJson')]
    public function SignupjsonUser(Request $req, NormalizerInterface $Normalizer, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Create a new utilisateur (user) entity
        $utilisateur = new Utilisateur();
        $utilisateur->setEmail($req->get('email'));
        $utilisateur->setPassword(
            $userPasswordHasher->hashPassword(
                $utilisateur,
                $req->get('password')
            )
        );
        $utilisateur->setNom($req->get('nom'));
        $utilisateur->setPrenomUser($req->get('prenomUser'));
        $utilisateur->setRoles($req->get('role'));
        $utilisateur->setIsActif(true);
        $utilisateur->setDateInscri(new \DateTime());


        // Persist the new user entity
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        // Normalize the response and send it as a JSON response
        $json = $Normalizer->normalize($utilisateur, 'json');
        return new JsonResponse($json);
    }
}
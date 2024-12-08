<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class FrontCoursController extends AbstractController
{
    private string $stripePublicKey;
    private string $stripeSecretKey;

    public function __construct(string $stripePublicKey, string $stripeSecretKey)
    {
        // Récupération des clés Stripe injectées depuis services.yaml
        $this->stripePublicKey = $stripePublicKey;
        $this->stripeSecretKey = $stripeSecretKey;
    }

    #[Route('/front/cours', name: 'front_cours_index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->findAll();
        return $this->render('front_cours/index.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/front/cours/{id}', name: 'front_cours_show', methods: ['GET'])]
    public function show(int $id, CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->find($id);

        if (!$cours) {
            throw $this->createNotFoundException('Cours non trouvé');
        }

        return $this->render('front_cours/show.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/front/cours/{id}/acheter', name: 'front_cours_acheter', methods: ['GET'])]
    public function acheter(Cours $cours): Response
    {
        return $this->render('front_cours/acheter.html.twig', [
            'cours' => $cours,
            'stripe_public_key' => $this->stripePublicKey, // Passer la clé publique à Twig
        ]);
    }

    #[Route('/front/cours/{id}/paiement', name: 'front_cours_paiement', methods: ['POST'])]
    public function createCheckoutSession(Cours $cours): JsonResponse
    {
        // Utiliser la clé privée Stripe
        Stripe::setApiKey($this->stripeSecretKey);

        $YOUR_DOMAIN = 'http://127.0.0.1:8000'; // Remplacez par votre domaine en production

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $cours->getTitreCours(),
                    ],
                    'unit_amount' => $cours->getPrix() * 100, // Convertir le prix en centimes
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/front/cours/paiement/success',
            'cancel_url' => $YOUR_DOMAIN . '/front/cours/paiement/cancel',
        ]);

        return new JsonResponse(['id' => $checkoutSession->id]);
    }

    #[Route('/front/cours/paiement/success', name: 'front_cours_paiement_success', methods: ['GET'])]
    public function paymentSuccess(): Response
    {
        return $this->render('front_cours/paiement_success.html.twig', [
            'message' => 'Votre paiement a été traité avec succès. Merci pour votre achat !',
        ]);
    }

    #[Route('/front/cours/paiement/cancel', name: 'front_cours_paiement_cancel', methods: ['GET'])]
    public function paymentCancel(): Response
    {
        return $this->render('front_cours/paiement_cancel.html.twig', [
            'message' => 'Le paiement a été annulé. Vous pouvez réessayer à tout moment.',
        ]);
    }
}

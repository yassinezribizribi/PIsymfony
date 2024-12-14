<?php

namespace App\Controller;
use App\Entity\Participation; // Pour la classe Participation
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EvenementRepository;
use App\Entity\Evenement;
use Symfony\Component\HttpFoundation\JsonResponse;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Repository\ParticipationRepository;

class EvenementfrontController extends AbstractController
{
    private string $stripePublicKey;
    private string $stripeSecretKey;
    public function __construct(string $stripePublicKey, string $stripeSecretKey)
    {
        // Récupération des clés Stripe injectées depuis services.yaml
        $this->stripePublicKey = $stripePublicKey;
        $this->stripeSecretKey = $stripeSecretKey;
    }
   
    #[Route('/events', name: 'page_events')]
    public function events(EvenementRepository $evenementRepository,ParticipationRepository $participationRepository): Response
    {
        // Récupérer les événements depuis la base de données
    
        $evenementss = $evenementRepository->findAll();
        $participationCounts = $participationRepository->getParticipationCounts();

        // Passer la variable `evenementss` au template
        return $this->render('evenementfront/events.html.twig', [
            'participationCounts' => $participationCounts,
            'evenementss' => $evenementss,
        ]);
    }
    #[Route('student/{id}', name: 'app_evenement_show_student', methods: ['GET'])]
    public function showStudent(Evenement $evenement): Response
    {
        return $this->render('evenementfront/show-student.html.twig', [
            'evenement' => $evenement,
            'stripe_public_key' => $this->stripePublicKey,
        ]);
    }
      

    #[Route('/front/evenement/{id}/paiement', name: 'front_evenement_paiement', methods: ['POST'])]
    public function createCheckoutSession(Evenement $evenement): JsonResponse
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
                        'name' => $evenement->getTitreEven(),
                    ],
                    'unit_amount' => $evenement->getPrixEvenement() * 100, // Convertir le prix en centimes
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' =>$YOUR_DOMAIN . '/front/evenement/' . $evenement->getId() . '/paiement/success',
            'cancel_url' => $YOUR_DOMAIN . '/front/evenement/paiement/cancel',
        ]);

        return new JsonResponse(['id' => $checkoutSession->id]);
    }

    #[Route('/front/evenement/{id}/paiement/success', name: 'front_evenement_paiement_success', methods: ['GET'])]
public function paymentSuccess(int $id, EvenementRepository $evenementRepository, EntityManagerInterface $entityManager): Response
{
    // Récupérer l'événement correspondant à l'ID
    $evenement = $entityManager->getRepository(Evenement::class)->find($id);

    if (!$evenement) {
        // L'événement n'existe pas, rediriger ou afficher un message d'erreur
        $this->addFlash('error', 'L\'événement n\'a pas été trouvé.');
        return $this->redirectToRoute('page_events');
    }

    // Créer une nouvelle participation
    $participation = new Participation();
    $participation->setDateParticipation(new \DateTime()); // Date de participation
    $participation->setEvenement($evenement); // Lier l'événement
    $participation->setUtilisateur($this->getUser()); // Lier l'utilisateur connecté

    // Sauvegarder la participation
    $entityManager->persist($participation);
    $entityManager->flush();

    // Ajouter un message de succès
    $this->addFlash('success', 'Vous avez bien été inscrit à l\'événement.');

    // Rediriger vers la liste des participations
    return $this->redirectToRoute('page_events');
}


    #[Route('/front/evenement/paiement/cancel', name: 'front_evenement_paiement_cancel', methods: ['GET'])]
    public function paymentCancel(): Response
    {
        return $this->render('evenementfront/paiement_cancel.html.twig', [
            'message' => 'Le paiement a été annulé. Vous pouvez réessayer à tout moment.',
        ]);
    }
}

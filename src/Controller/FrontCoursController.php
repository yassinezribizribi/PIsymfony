<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontCoursController extends AbstractController
{
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
        $cour = $coursRepository->find($id);

        if (!$cour) {
            throw $this->createNotFoundException('Cours non trouvé');
        }

        return $this->render('front_cours/show.html.twig', [
            'cours' => $cour,
        ]);
    }

    #[Route('/front/cours/{id}/acheter', name: 'front_cours_acheter', methods: ['GET'])]
public function acheter(Cours $cours): Response
{
    // Logique pour "acheter un cours" (simulé pour l'instant)
    return $this->render('front_cours/acheter.html.twig', [
        'cours' => $cours,
    ]);
}


    #[Route('/front/cours/{id}/paiement', name: 'front_cours_paiement', methods: ['GET', 'POST'])]
    public function paiement(Request $request, Cours $cours): Response
    {
        if ($request->isMethod('POST')) {
            // Simulation du traitement de paiement
            $cardNumber = $request->request->get('cardNumber');
            $expiryDate = $request->request->get('expiryDate');
            $cvv = $request->request->get('cvv');

            if ($cardNumber === '4111111111111111' && $expiryDate === '12/23' && $cvv === '123') {
                // Paiement simulé réussi
                $this->addFlash('success', 'Votre paiement a été effectué avec succès.');
                return $this->redirectToRoute('front_cours_index');
            } else {
                // Paiement simulé échoué
                $this->addFlash('error', 'Une erreur est survenue pendant le paiement. Veuillez vérifier vos informations.');
            }
        }

        return $this->render('front/cours/paiement.html.twig', [
            'cours' => $cours,
        ]);
    }
}

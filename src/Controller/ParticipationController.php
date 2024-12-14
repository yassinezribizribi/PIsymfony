<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Participation;
use App\Form\ParticipationType;
use App\Repository\ParticipationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/participation')]
final class ParticipationController extends AbstractController{
    #[Route( name: 'app_participation_index', methods: ['GET'])]
    public function index(ParticipationRepository $participationRepository): Response
    {
        // Appel des deux méthodes
        $participationsDetails = $participationRepository->findParticipationDetails();
        $participationsAllDetails = $participationRepository->findAllWithEventDetails();
    
        // Rendu du template avec les deux variables distinctes
        return $this->render('participation/index.html.twig', [
            'participationsDetails' => $participationsDetails,
            'participationsAllDetails' => $participationsAllDetails,
        ]);
    }

    
    #[Route('/{id}', name: 'app_participation_show', methods: ['GET'])]
    public function show(Participation $participation): Response
    {
        return $this->render('participation/show.html.twig', [
            'participation' => $participation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participation/edit.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_delete', methods: ['POST'])]
    public function delete(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($participation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
    }
  
#[Route('/create/{id}', name: 'app_participation_create', methods: ['GET'])]
public function create(int $id, EntityManagerInterface $entityManager, ParticipationRepository $participationRepository): Response
{
    // Récupérer l'événement correspondant à l'ID
    $evenement = $entityManager->getRepository(Evenement::class)->find($id);

    if (!$evenement) {
        throw $this->createNotFoundException('The event does not exist');
    }

    // Créer une nouvelle participation
    $participation = new Participation();
    $participation->setDateParticipation(new \DateTime()); // Date actuelle de la participation
    $participation->setEvenement($evenement); // Associer l'événement à la participation
    $participation->setUtilisateur($this->getUser());  // L'utilisateur connecté

    // Persister et sauvegarder la participation
    $entityManager->persist($participation);
    $entityManager->flush();

    // Ajouter un message flash pour informer l'utilisateur
    $this->addFlash('success', 'Vous avez bien participé à l\'événement !');

    // Rediriger vers la liste des participations (ou une autre page selon le besoin)
    return $this->redirectToRoute('page_events');
}

}

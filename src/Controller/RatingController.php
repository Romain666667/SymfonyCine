<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingForm;
use App\Repository\MoviesRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/rating')]
final class RatingController extends AbstractController
{
    #[Route('/add/{id}', name: 'app_rating_add', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(int $id, Request $request, MoviesRepository $moviesRepository, EntityManagerInterface $entityManager): Response
    {
        $movie = $moviesRepository->find($id);

        if (!$movie) {
            throw $this->createNotFoundException('Film introuvable.');
        }

        $rating = new Rating();
        $form   = $this->createForm(RatingForm::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setMovie($movie);
            $rating->setMovieLover($this->getUser());

            $entityManager->persist($rating);
            $entityManager->flush();

            $this->addFlash('success', 'Votre avis a bien été ajouté !');

            return $this->redirectToRoute('app_movies_show', ['id' => $movie->getId()]);
        }

        return $this->render('rating/add.html.twig', [
            'movie' => $movie,
            'form'  => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_rating_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(int $id, Request $request, RatingRepository $ratingRepository, EntityManagerInterface $entityManager): Response
    {
        $rating = $ratingRepository->find($id);

        if (!$rating) {
            throw $this->createNotFoundException('Note introuvable.');
        }

        // Seul l'auteur ou un admin peut supprimer
        if ($rating->getMovieLover() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $movieId = $rating->getMovie()->getId();

        if ($this->isCsrfTokenValid('delete' . $rating->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rating);
            $entityManager->flush();

            $this->addFlash('success', 'Avis supprimé.');
        }

        return $this->redirectToRoute('app_movies_show', ['id' => $movieId]);
    }
}

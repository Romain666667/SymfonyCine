<?php

namespace App\Controller;

use App\Entity\Movies;
use App\Form\MoviesType;
use App\Repository\MoviesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RatingRepository;
use Psr\Log\LoggerInterface;

#[Route('/movies')]
final class MoviesController extends AbstractController
{
    #[Route(name: 'app_movies_index', methods: ['GET'])]
    public function index(Request $request, MoviesRepository $moviesRepository, LoggerInterface $logger): Response
    {
        $searchTerm = $request->query->get('search', '');

        $logger->info('ℹ️ Accès à la liste des films', ['search' => $searchTerm ?: 'aucune']);

        try {
            $movies = $searchTerm
                ? $moviesRepository->search($searchTerm)
                : $moviesRepository->findAll();
        } catch (\Throwable $e) {
            $logger->error('❌ Erreur lors de la récupération des films', ['exception' => $e->getMessage()]);
            throw $e;
        }

        return $this->render('movies/index.html.twig', [
            'movies'     => $movies,
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/new', name: 'app_movies_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $movie = new Movies();
        $form = $this->createForm(MoviesType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $logger->warning('⚠️ Formulaire de création invalide', ['errors' => (string) $form->getErrors(true)]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($movie);
                $entityManager->flush();
                $logger->info('✅ Film créé avec succès', ['title' => $movie->getTitle()]);
            } catch (\Throwable $e) {
                $logger->error('❌ Erreur lors de la création du film', [
                    'title'     => $movie->getTitle(),
                    'exception' => $e->getMessage(),
                ]);
                throw $e;
            }
            return $this->redirectToRoute('app_movies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('movies/new.html.twig', [
            'movie' => $movie,
            'form'  => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_movies_show', methods: ['GET'])]
    public function show(int $id, MoviesRepository $moviesRepository, RatingRepository $ratingRepository, LoggerInterface $logger): Response
    {
        $movie = $moviesRepository->find($id);

        if (!$movie) {
            $logger->warning('⚠️ Film introuvable', ['id' => $id]);
            throw $this->createNotFoundException('Film introuvable.');
        }

        $logger->info('ℹ️ Consultation d\'un film', ['id' => $id, 'title' => $movie->getTitle()]);

        try {
            $ratings  = $ratingRepository->findByMovie($movie);
            $avgScore = $ratingRepository->findAverageScoreByMovie($movie);
        } catch (\Throwable $e) {
            $logger->error('❌ Erreur lors de la récupération des ratings', [
                'movie_id'  => $id,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }

        return $this->render('movies/show.html.twig', [
            'movie'    => $movie,
            'ratings'  => $ratings,
            'avgScore' => $avgScore,
        ]);
    }

    #[Route('/before/{year}', name: 'app_movies_before', methods: ['GET'])]
    public function findMoviesReleasebefore(int $year, MoviesRepository $moviesRepository, LoggerInterface $logger): Response
    {
        $logger->info('ℹ️ Recherche de films par année', ['before' => $year]);

        try {
            $movies = $moviesRepository->findMoviesReleasebefore($year);
        } catch (\Throwable $e) {
            $logger->error('❌ Erreur lors de la recherche par année', [
                'year'      => $year,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }

        return $this->render('movies/index.html.twig', [
            'movies'     => $movies,
            'searchTerm' => 'Movies released before ' . $year,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_movies_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Movies $movie, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $logger->warning('⚠️ Accès non autorisé à l\'édition', ['movie_id' => $movie->getId()]);
            throw $this->createAccessDeniedException('You do not have permission to access this page.');
        }

        $form = $this->createForm(MoviesType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
                $logger->info('✅ Film modifié avec succès', ['id' => $movie->getId(), 'title' => $movie->getTitle()]);
            } catch (\Throwable $e) {
                $logger->error('❌ Erreur lors de la modification du film', [
                    'id'        => $movie->getId(),
                    'exception' => $e->getMessage(),
                ]);
                throw $e;
            }
            return $this->redirectToRoute('app_movies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('movies/edit.html.twig', [
            'movie' => $movie,
            'form'  => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_movies_delete', methods: ['POST'])]
    public function delete(Request $request, Movies $movie, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $logger->warning('⚠️ Tentative de suppression non autorisée', ['movie_id' => $movie->getId()]);
            throw $this->createAccessDeniedException('You do not have permission to access this page.');
        }

        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($movie);
                $entityManager->flush();
                $logger->info('✅ Film supprimé avec succès', ['id' => $movie->getId(), 'title' => $movie->getTitle()]);
            } catch (\Throwable $e) {
                $logger->error('❌ Erreur lors de la suppression du film', [
                    'id'        => $movie->getId(),
                    'exception' => $e->getMessage(),
                ]);
                throw $e;
            }
        } else {
            $logger->warning('⚠️ Token CSRF invalide pour la suppression', ['movie_id' => $movie->getId()]);
        }

        return $this->redirectToRoute('app_movies_index', [], Response::HTTP_SEE_OTHER);
    }
}

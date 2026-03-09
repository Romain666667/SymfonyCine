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
use App\Service\TMDBApi;
use Symfony\Component\Security\Http\Attribute\IsGranted;




#[Route('/movies')]
final class MoviesController extends AbstractController
{
    #[Route(name: 'app_movies_index', methods: ['GET'])]
    public function index(TMDBApi $tmdb): Response
    {
        $movies = $tmdb->discover(); // ← récupère les films depuis TMDB
    
        return $this->render('movies/index.html.twig', [
            'movies' => $movies,
        ]);
    }
    

    #[Route('/new', name: 'app_movies_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You do not have permission to access this page.');
        }

        $movie = new Movies();
        $form = $this->createForm(MoviesType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movie);
            $entityManager->flush();
            return $this->redirectToRoute('app_movies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('movies/new.html.twig', [
            'movie' => $movie,
            'form'  => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_movies_show', methods: ['GET'])]
    public function show(int $id, TMDBApi $tmdb): Response
    {
        $movie = $tmdb->searchById($id);  // ← cherche dans TMDB, pas en BDD

        return $this->render('movies/show.html.twig', [
            'movie' => $movie,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_movies_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Movies $movie, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You do not have permission to access this page.');
        }
        $form = $this->createForm(MoviesType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_movies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('movies/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_movies_delete', methods: ['POST'])]
    public function delete(Request $request, Movies $movie, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You do not have permission to access this page.');
        }
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($movie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_movies_index', [], Response::HTTP_SEE_OTHER);
    }
}

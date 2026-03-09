<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RatingController extends AbstractController
{
    #[Route('/rating/form', name: 'rating_form',)]
    public function ratingForm(): Response
    {
        $response = new Response('Formulaire');
        return $response;
    }


    #[Route('/rating', name: 'rating_list')]
    public function ratingList(): Response
    {
        $response = new Response('Voici la liste des notes');
        return $response;
    }
}

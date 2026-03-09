<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CollectionController extends AbstractController
{
    #[Route('/collection/list', name: 'collection_list')]
    public function collectionList(): Response
    {
        $response = new Response('Listes de la collection');
        return $response;
    }

    #[Route('/collection/{idCollection}', name: 'collection_id',
    requirements : ['idCollection'=> '\d+'],
    defaults : ['idCollection'=> '1']
    )]
    public function collectionID(int $idCollection): Response
    {
        $response = new Response(content: 'Vous avez séléctionné la collection avec lid '. $idCollection);
        return $response;
    }

    #[Route('/collection/form', name: 'collection_form')]
    public function collectionForm(): Response
    {
        $response = new Response(content: 'Formulaire');
        return $response;
    }


}

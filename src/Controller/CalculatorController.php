<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Slugifier;
//use App\;

final class CalculatorController extends AbstractController
{
    #[Route('/addition/{param1}/{param2}',
    name: 'app_calculatoraddition',
    defaults: ['param1' => 1, 'param2' => 2],
    requirements : [
        'param1' => '\d+',
        'param2' => '\d+'
    ]
    )]
    public function addition(int $param1, int $param2, LoggerInterface $logger): JsonResponse
    {
        $slugifier = new Slugifier();
        $slug = $slugifier->slugify('Salut les amis COMMENT vous allez');
        $logger->error($slug.'➕➕➕➕');
        $resultat = $param1 + $param2;
        $response = new JsonResponse('Le résultat de l addition ' . $param1 .'+'. $param2 . ' est ' .$resultat);
        return $response;
    }

    #[Route('/soustraction/{param1}/{param2}',
    name: 'app_calculatorsoustraction',
    defaults: ['param1' => 1, 'param2' => 2],
    requirements : [
        'param1' => '\d+',
        'param2' => '\d+'
    ]
    )]
    public function soustraction(int $param1, int $param2): Response
    {
        $resultat = $param1 - $param2;
        $response = new Response('Le résultat de la soustraction ' . $param1 .'-'. $param2 . ' est ' .$resultat);
        return $response;
    }

    #[Route('/multiplication/{param1}/{param2}',
    name: 'app_calculator',
    defaults: ['param1' => 1, 'param2' => 2])]
    public function multiplication(int $param1, int $param2): Response
    {
        $resultat = $param1 * $param2;
        $response = new Response('Le résultat de la multiplication ' . $param1 .'x'. $param2 . ' est ' .$resultat);
        return $response;
    }

    #[Route('/password',
    name: 'password',)]
    public function password(): Response
    {
        //$passwordService = new PasswordService();
        $password = $passwordService->generatePassword(12, 3);
        $strength = $passwordService->checkPasswordStrength($password);
        $hash = $passwordService->hashPassword($password, 'bcrypt');

        $response = new Response('mot de passe : ' .$password . 'compléxité : '.$strength . 'hash : ' . $hash);
        return $response;
    }
    
    
}

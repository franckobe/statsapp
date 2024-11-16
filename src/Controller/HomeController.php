<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    #[Template(template: 'home.html.twig')]
    public function index(): array
    {
        return [
            'message' => 'Moyennes',
        ];
    }

    #[Route('/match-par-match', name: 'games', methods: ['GET'])]
    #[Template(template: 'home.html.twig')]
    public function games(): array
    {
        return [
            'message' => 'Match par match',
        ];
    }

    #[Route('/integration-match', name: 'addGame', methods: ['GET'])]
    #[Template(template: 'addGame.html.twig')]
    public function addGame(): array
    {
        return [
            'message' => 'Ajout match',
        ];
    }

}
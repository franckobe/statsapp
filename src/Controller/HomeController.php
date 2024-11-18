<?php

namespace App\Controller;

use App\DTO\PlayerAllStatsDTO;
use App\Repository\GameStatsRepository;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'teams', methods: ['GET'])]
    #[Template(template: 'teams.html.twig')]
    public function teams(TeamRepository $teamRepository): array
    {
        return [
            'teams' => $teamRepository->findAllTeams(),
            'breadcrumb' => [
                [
                    'title' => 'Equipes',
                ]
            ]
        ];
    }

    #[Route('/equipes/{teamId}/stats-individuelles/{method}', name: 'teamPlayersStats')]
    #[Template(template: 'teamPlayersStats.html.twig')]
    public function teamPlayersStats(int $teamId, string $method, GameStatsRepository $gameStatsRepository, TeamRepository $teamRepository): array
    {
        $stats = $gameStatsRepository->getCalculatedStats($teamId, $method);
        $team = $teamRepository->find($teamId);
        return [
            'stats' => $stats,
            'breadcrumb' => [
                [
                    'path' => $this->generateUrl('teams'),
                    'title' => 'Equipes',
                ],
                [
                    'title' => $team->getName(),
                ]
            ]
        ];
    }

    #[Route('/matchs', name: 'games', methods: ['GET'])]
    #[Template(template: 'games.html.twig')]
    public function games(): array
    {
        return [
            'message' => 'Match par match',
        ];
    }

    #[Route('/joueurs', name: 'players', methods: ['GET'])]
    #[Template(template: 'players.html.twig')]
    public function players(PlayerRepository $playerRepository): array
    {
        return [
            'players' => $playerRepository->findAllPlayers(),
            'breadcrumb' => [
                [
                    'title' => 'Joueurs',
                ],
            ]
        ];
    }

    #[Route('/joueurs/{playerId}', name: 'player', methods: ['GET'])]
    #[Template(template: 'player.html.twig')]
    public function player(int $playerId, PlayerRepository $playerRepository, GameStatsRepository $gameStatsRepository): array
    {
        $player = $playerRepository->find($playerId);
        $playerAverages = $gameStatsRepository->getCalculatedStats($player->getTeam()->getId(), 'AVG', $player->getId());
        $playerTotals = $gameStatsRepository->getCalculatedStats($player->getTeam()->getId(), 'SUM', $player->getId());
        $playerGames = $gameStatsRepository->getCalculatedStats($player->getTeam()->getId(), '', $player->getId());

        $playerAllStatsDTO = new PlayerAllStatsDTO($player, $playerAverages, $playerTotals, $playerGames);

        return [
            'player' => $playerAllStatsDTO,
            'breadcrumb' => [
                [
                    'path' => $this->generateUrl('players'),
                    'title' => 'Joueurs',
                ],
                [
                    'title' => $player->getName(),
                ],
            ]
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
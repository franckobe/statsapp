<?php

namespace App\Controller;

use App\Collection\GameStatsCollection;
use App\DTO\GameStatsCollectionDTO;
use App\DTO\PlayerAllStatsDTO;
use App\Repository\GameStatsRepository;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;
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
    public function teamPlayersStats(int $teamId, string $method, TeamRepository $teamRepository): array
    {
        $team = $teamRepository->find($teamId);

        $playerStats = [];
        foreach ($team->getPlayers() as $player) {
            $gameStatsCollection = new GameStatsCollection();
            $gameStatsCollection->addAll($player->getGameStats());
            $playerStats[] = $gameStatsCollection->getAllGamesStatsCalculated($player, $method);
        }

        usort($playerStats, function ($a, $b) {
            return $b->points <=> $a->points;
        });

        return [
            'stats' => $playerStats,
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
    public function player(int $playerId, PlayerRepository $playerRepository): array
    {
        $player = $playerRepository->find($playerId);

        $gameStatsCollection = new GameStatsCollection();
        $gameStatsCollection->addAll($player->getGameStats());
        $playerAverages = $gameStatsCollection->getAllGamesStatsCalculated($player, 'AVG');
        $playerTotals = $gameStatsCollection->getAllGamesStatsCalculated($player, 'SUM');
        $playerAveragesPer40min = $gameStatsCollection->getAllGamesStatsCalculated($player, 'MINUTE');

        $criteria = Criteria::create()->orderBy(["game.number" => Order::Ascending]);
        $sortedResults = $player->getGameStats()->matching($criteria);
        $playerGamesStats = GameStatsCollectionDTO::fromCollection($sortedResults);

        $playerAllStatsDTO = new PlayerAllStatsDTO($player, $playerAverages, $playerTotals, $playerAveragesPer40min, $playerGamesStats);

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
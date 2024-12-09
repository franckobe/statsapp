<?php

namespace App\Controller;

use App\Collection\GameStatsCollection;
use App\DTO\GameStatsCollectionDTO;
use App\DTO\PlayerAllStatsDTO;
use App\Enum\CalculationMethod;
use App\Repository\GameRepository;
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
            'calculationMethods' => CalculationMethod::cases(),
            'breadcrumb' => [
                [
                    'title' => 'Equipes',
                ]
            ]
        ];
    }

    #[Route('/equipes/{teamId}/stats-individuelles/{method}', name: 'teamPlayersStats', defaults: ['method' => CalculationMethod::AVG->name])]
    #[Template(template: 'teamPlayersStats.html.twig')]
    public function teamPlayersStats(int $teamId, CalculationMethod $method, TeamRepository $teamRepository): array
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
            'calculationMethods' => CalculationMethod::cases(),
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
    public function games(GameRepository $gameRepository): array
    {
        $games = $gameRepository->findAllGames();

        return [
            'games' => $games,
            'breadcrumb' => [
                [
                    'title' => 'Matchs',
                ]
            ]
        ];
    }

    #[Route('/matchs/{gameId}', name: 'game', methods: ['GET'])]
    #[Template(template: 'game.html.twig')]
    public function game(int $gameId, GameRepository $gameRepository): array
    {
        $game = $gameRepository->find($gameId);

        $criteria = Criteria::create()->orderBy(["points" => Order::Descending]);
        $sortedResults = $game->getGameStats()->matching($criteria);
        $boxScore = GameStatsCollectionDTO::fromCollection($sortedResults);

        return [
            'game' => $game,
            'boxscore' => $boxScore,
            'breadcrumb' => [
                [
                    'path' => $this->generateUrl('games'),
                    'title' => 'Matchs',
                ],
                [
                    'title' => 'Journée ' . $game->getNumber(),
                ],
            ]
        ];
    }

    #[Route('/joueurs', name: 'players', methods: ['GET'])]
    #[Template(template: 'players.html.twig')]
    public function players(PlayerRepository $playerRepository): array
    {
        return [
            'players' => $playerRepository->findAllPlayers(),
            'calculationMethods' => CalculationMethod::cases(),
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
        $playerAverages = $gameStatsCollection->getAllGamesStatsCalculated($player, CalculationMethod::AVG);
        $playerTotals = $gameStatsCollection->getAllGamesStatsCalculated($player, CalculationMethod::SUM);
        $playerAveragesPer40min = $gameStatsCollection->getAllGamesStatsCalculated($player, CalculationMethod::MINUTE);

        $criteria = Criteria::create()->orderBy(["game.number" => Order::Ascending]);
        $sortedResults = $player->getGameStats()->matching($criteria);
        $playerGamesStats = GameStatsCollectionDTO::fromCollection($sortedResults);

        $playerAllStatsDTO = new PlayerAllStatsDTO($player, $playerAverages, $playerTotals, $playerAveragesPer40min, $playerGamesStats);

        return [
            'playerStats' => $playerAllStatsDTO,
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

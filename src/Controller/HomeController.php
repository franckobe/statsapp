<?php

namespace App\Controller;

use App\Collection\GameStatsCollection;
use App\DTO\GameStatsCollectionDTO;
use App\DTO\PlayerAllStatsDTO;
use App\Entity\Game;
use App\Entity\GameStats;
use App\Enum\CalculationMethod;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Tool\Charts\PlayerGamesChart;
use App\Tool\Charts\PlayerRadarChart;
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

    #[Route('/equipes/{teamId}/{method}', name: 'teamPlayersStats', defaults: ['method' => CalculationMethod::AVG->name])]
    #[Template(template: 'teamPlayersStats.html.twig')]
    public function teamPlayersStats(int $teamId, CalculationMethod $method, TeamRepository $teamRepository, GameRepository $gameRepository): array
    {
        $team = $teamRepository->find($teamId);

        $playersStats = [];
        $teamStatsCollection = new GameStatsCollection();
        foreach ($team->getPlayers() as $player) {
            $gameStatsCollection = new GameStatsCollection();
            $gameStatsCollection->addAll($player->getGameStats());
            $teamStatsCollection->addAll($player->getGameStats());
            $playersStats[] = $gameStatsCollection->getAllPlayerGamesStatsCalculated($method, $player);
        }

        usort($playersStats, function ($a, $b) {
            return $b->points <=> $a->points;
        });

        $opponentTeamStatsCollection = new GameStatsCollection();
        foreach ($gameRepository->findAll() as $game) {
            if ($game->getHomeTeam() !== $team && $game->getAwayTeam() !== $team) {
                continue;
            }
            foreach ($game->getGameStats() as $gameStats) {
                if ($gameStats->getPlayer()->getTeam()->getId() === $team->getId()) {
                    continue;
                }
                $opponentTeamStatsCollection->add($gameStats);
            }
        }

        $teamStats = null;
        $opponentTeamsStats = null;
        if ($method !== CalculationMethod::MINUTE) {
            $teamStats = $teamStatsCollection->getAllTeamGamesStatsCalculated($method, $team);
            $teamStats->plusMinus = $teamStats->plusMinus / 5;
            $opponentTeamsStats = $opponentTeamStatsCollection->getAllOpponentTeamsGamesStatsCalculated($method);
            $opponentTeamsStats->plusMinus = $opponentTeamsStats->plusMinus / 5;
        }

        return [
            'stats' => $playersStats,
            'teamStats' => $teamStats,
            'opponentTeamsStats' => $opponentTeamsStats,
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

        $homeTeamGameStatsCollection = new GameStatsCollection();
        $awayTeamGameStatsCollection = new GameStatsCollection();
        foreach ($game->getGameStats() as $gameStats) {
            if ($gameStats->getPlayer()->getTeam()->getId() === $game->getHomeTeam()->getId()) {
                $homeTeamGameStatsCollection->add($gameStats);
            }
            if ($gameStats->getPlayer()->getTeam()->getId() === $game->getAwayTeam()->getId()) {
                $awayTeamGameStatsCollection->add($gameStats);
            }
        }

        $homeTeamTotals = $homeTeamGameStatsCollection->getAllTeamGamesStatsCalculated(CalculationMethod::SUM, $game->getHomeTeam());
        $homeTeamTotals->plusMinus = $homeTeamTotals->plusMinus / 5;
        $awayTeamTotals = $awayTeamGameStatsCollection->getAllTeamGamesStatsCalculated(CalculationMethod::SUM, $game->getAwayTeam());
        $awayTeamTotals->plusMinus = $awayTeamTotals->plusMinus / 5;

        return [
            'game' => $game,
            'boxscore' => $boxScore,
            'totals' => [
                $game->getHomeTeam()->getId() => $homeTeamTotals,
                $game->getAwayTeam()->getId() => $awayTeamTotals,
            ],
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
        $playerAverages = $gameStatsCollection->getAllPlayerGamesStatsCalculated(CalculationMethod::AVG, $player);
        $playerTotals = $gameStatsCollection->getAllPlayerGamesStatsCalculated(CalculationMethod::SUM, $player);
        $playerAveragesPer40min = $gameStatsCollection->getAllPlayerGamesStatsCalculated(CalculationMethod::MINUTE, $player);

        $criteria = Criteria::create()->orderBy([
            "game.phase" => Order::Ascending,
            "game.number" => Order::Ascending
        ]);
        $sortedResults = $player->getGameStats()->matching($criteria);
        $playerGamesStats = GameStatsCollectionDTO::fromCollection($sortedResults);

        $playerAllStatsDTO = new PlayerAllStatsDTO($player, $playerAverages, $playerTotals, $playerAveragesPer40min, $playerGamesStats);

        return [
            'playerStats' => $playerAllStatsDTO,
            'charts' => [
                new PlayerRadarChart($player, $playerAverages),
                new PlayerGamesChart($playerGamesStats),
            ],
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

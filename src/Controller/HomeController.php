<?php

namespace App\Controller;

use App\DTO\PlayerStatsDTO;
use App\Entity\Player;
use App\Repository\GameStatsRepository;
use App\Repository\PlayerRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    #[Template(template: 'home.html.twig')]
    public function index(GameStatsRepository $gameStatsRepository): array
    {

        $results = $gameStatsRepository->createQueryBuilder('gs')
                            ->select('
            p.id as player_id,
            p.name as player_name,
            count(gs.id) AS games_played,
            AVG(gs.minutes) AS minutes,
            AVG(gs.points) AS points,
            AVG(gs.fgm2) AS fgm2,
            AVG(gs.fga2) AS fga2,
            AVG(gs.fgm3) AS fgm3,
            AVG(gs.fga3) AS fga3,
            AVG(gs.ftm) AS ftm,
            AVG(gs.fta) AS fta,
            AVG(gs.offensiveRebound) AS offensive_rebound,
            AVG(gs.defensiveRebound) AS defensive_rebound,
            AVG(gs.foul) AS foul,
            AVG(gs.foul_provoked) AS foul_provoked,
            AVG(gs.steal) AS steal,
            AVG(gs.turnover) AS turnover,
            AVG(gs.assist) AS assist,
            AVG(gs.block) AS block,
            AVG(gs.blockReceived) AS block_received,
            AVG(gs.efficiency) AS efficiency,
            AVG(gs.plusMinus) AS plus_minus
        ')
            ->join('gs.player', 'p')
            ->join('p.team', 't')
            ->where('gs.minutes > 0')
            ->where('t.name = :teamName')
            ->setParameter('teamName', 'Istres Sports BC')
            ->groupBy('p.id')
            ->orderBy('points', 'DESC')
            ->getQuery()
            ->getResult();

        $averages = array_map(fn($result) => new PlayerStatsDTO(
            player: Player::hydrate($result['player_id'], $result['player_name']),
            gamesPlayed: (int) $result['games_played'],
            minutes: round($result['minutes'], 1),
            points: round($result['points'], 1),
            fgm2: round($result['fgm2'], 1),
            fga2: round($result['fga2'], 1),
            fgm3: round($result['fgm3'], 1),
            fga3: round($result['fga3'], 1),
            ftm: round($result['ftm'], 1),
            fta: round($result['fta'], 1),
            offensiveRebound: round($result['offensive_rebound'], 1),
            defensiveRebound: round($result['defensive_rebound'], 1),
            foul: round($result['foul'], 1),
            foulProvoked: round($result['foul_provoked'], 1),
            steal: round($result['steal'], 1),
            turnover: round($result['turnover'], 1),
            assist: round($result['assist'], 1),
            block: round($result['block'], 1),
            blockReceived: round($result['block_received'], 1),
            efficiency: round($result['efficiency'], 1),
            plusMinus: round($result['plus_minus'], 1),
        ), $results);

        return [
            'averages' => $averages,
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

    #[Route('/joueurs', name: 'players', methods: ['GET'])]
    #[Template(template: 'players.html.twig')]
    public function players(PlayerRepository $playerRepository): array
    {
        return [
            'players' => $playerRepository
                            ->createQueryBuilder('p')
                            ->join('p.team', 't')
                            ->where('t.name = :teamName')
                            ->setParameter('teamName', 'Istres Sports BC')
                            ->orderBy('p.name', 'ASC')
                            ->getQuery()->getResult(),
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
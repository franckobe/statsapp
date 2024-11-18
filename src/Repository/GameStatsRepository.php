<?php

namespace App\Repository;

use App\DTO\PlayerStatsDTO;
use App\Entity\GameStats;
use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameStats>
 */
class GameStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameStats::class);
    }
    public function getCalculatedStats(int $teamId, string $method, ?int $playerId = null): array
    {
        if (!in_array($method, ['', 'AVG', 'SUM'])) {
            return [];
        }

        $gamesCol = 'count(gs.id)';
        if ($method === '') {
            $gamesCol = 'g.number';
        }

        $q = $this->createQueryBuilder('gs')
            ->select("
            p.id as player_id,
            p.name as player_name,
            t.id AS team_id,
            t.name AS team_name,
            CASE 
                WHEN ht.id = t.id THEN at.id
                ELSE ht.id 
            END AS opponent_team_id,
            CASE 
                WHEN ht.id = t.id THEN at.name
                ELSE ht.name 
            END AS opponent_team_name,
            ht.id as home_team_id,
            ht.name as home_team_name,
            at.id as away_team_id,
            at.name as away_team_name,
            {$gamesCol} AS games_played,
            {$method}(gs.minutes) AS minutes,
            {$method}(gs.points) AS points,
            {$method}(gs.fgm2) AS fgm2,
            {$method}(gs.fga2) AS fga2,
            {$method}(gs.fgm3) AS fgm3,
            {$method}(gs.fga3) AS fga3,
            {$method}(gs.ftm) AS ftm,
            {$method}(gs.fta) AS fta,
            {$method}(gs.offensiveRebound) AS offensive_rebound,
            {$method}(gs.defensiveRebound) AS defensive_rebound,
            {$method}(gs.foul) AS foul,
            {$method}(gs.foul_provoked) AS foul_provoked,
            {$method}(gs.steal) AS steal,
            {$method}(gs.turnover) AS turnover,
            {$method}(gs.assist) AS assist,
            {$method}(gs.block) AS block,
            {$method}(gs.blockReceived) AS block_received,
            {$method}(gs.efficiency) AS efficiency,
            {$method}(gs.plusMinus) AS plus_minus
        ")
            ->join('gs.player', 'p')
            ->join('p.team', 't')
            ->join('gs.game', 'g')
            ->join('g.homeTeam', 'ht')
            ->join('g.awayTeam', 'at')
            ->where('gs.minutes > 0')
            ->andWhere('p.team = :team_id')
            ->setParameter('team_id', $teamId);

        if ($playerId) {
            $q = $q->andWhere('p.id = :player_id')->setParameter('player_id', $playerId);
            if ($method !== '') {
                $q = $q->groupBy('p.id');
                $q = $q->orderBy('points', 'DESC');
            }
            else {
                $q = $q->orderBy('g.number', 'ASC');
            }
        }
        else {
            $q = $q->groupBy('p.id')->orderBy('points', 'DESC');
        }


        $results = $q->getQuery()->getResult();

        return array_map(fn($result) => new PlayerStatsDTO(
            player: Player::hydrate($result['player_id'], $result['player_name']),
            team: Team::hydrate($result['team_id'], $result['team_name']),
            opponentTeam: Team::hydrate($result['opponent_team_id'], $result['opponent_team_name']),
            home: (int)$result['team_id'] === (int)$result['home_team_id'],
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
    }
}

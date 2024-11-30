<?php

namespace App\Repository;

use App\DTO\GameStatsCalculatedDTO;
use App\Entity\Game;
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
        if (!in_array($method, ['AVG', 'SUM','MINUTE'])) {
            return [];
        }

        $coeff = '';
        if ($method === 'MINUTE') {
            $method = 'AVG';
            $coeff = '/(CASE WHEN gs.minutes = 0 THEN 1 ELSE gs.minutes END)*40';
        }

        $q = $this->createQueryBuilder('gs')
            ->select("
            p.id as player_id,
            g.id as game_id,
            count(gs.id) AS games_played,
            {$method}(gs.minutes{$coeff}) AS minutes,
            {$method}(gs.points{$coeff}) AS points,
            {$method}(gs.fgm2{$coeff}) AS fgm2,
            {$method}(gs.fga2{$coeff}) AS fga2,
            {$method}(gs.fgm3{$coeff}) AS fgm3,
            {$method}(gs.fga3{$coeff}) AS fga3,
            {$method}(gs.ftm{$coeff}) AS ftm,
            {$method}(gs.fta{$coeff}) AS fta,
            {$method}(gs.offensiveRebound{$coeff}) AS offensive_rebound,
            {$method}(gs.defensiveRebound{$coeff}) AS defensive_rebound,
            {$method}(gs.foul{$coeff}) AS foul,
            {$method}(gs.foulProvoked{$coeff}) AS foul_provoked,
            {$method}(gs.steal{$coeff}) AS steal,
            {$method}(gs.turnover{$coeff}) AS turnover,
            {$method}(gs.assist{$coeff}) AS assist,
            {$method}(gs.block{$coeff}) AS block,
            {$method}(gs.blockReceived{$coeff}) AS block_received,
            {$method}(gs.efficiency{$coeff}) AS efficiency,
            {$method}(gs.plusMinus{$coeff}) AS plus_minus
        ")
            ->join('gs.player', 'p')
            ->join('gs.game', 'g')
            ->join('p.team', 't')
            ->where('gs.minutes > 0')
            ->andWhere('p.team = :team_id')
            ->setParameter('team_id', $teamId);

        if ($playerId) {
            $q = $q->andWhere('p.id = :player_id')->setParameter('player_id', $playerId);
            $q = $q->groupBy('p.id');
            $q = $q->orderBy('points', 'DESC');
        }
        else {
            $q = $q->groupBy('p');
            $q = $q->orderBy('points', 'DESC');
        }


        $results = $q->getQuery()->getResult();

        return array_map(function($result) {

            $player = $this->getEntityManager()->getRepository(Player::class)->find($result['player_id']);

            return new GameStatsCalculatedDTO(
                player: $player,
                team: $player->getTeam(),
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
            );
        }, $results);
    }
}

<?php

namespace App\Collection;

use App\DTO\GameStatsCalculatedDTO;
use App\Entity\GameStats;
use App\Entity\Player;
use App\Entity\Team;
use App\Enum\CalculationMethod;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class GameStatsCollection
{
    private Collection $allGamesStats;

    public function __construct()
    {
        $this->allGamesStats = new ArrayCollection();
    }

    public function add(GameStats $gameStats): void
    {
        $this->allGamesStats->add($gameStats);
    }

    public function addAll(Collection $allGameStats): void
    {
        foreach ($allGameStats as $gameStats) {
            $this->add($gameStats);
        }
    }

    public function getOnlyPlayedGames(): ArrayCollection
    {
        return $this->allGamesStats->filter(function (GameStats $gameStats) {
            return $gameStats->getMinutes() > 0;
        });
    }

    public function getAllTeamGamesStatsCalculated(CalculationMethod $method, Team $team): GameStatsCalculatedDTO
    {
        return $this->getAllGamesStatsCalculated(method: $method, team: $team);
    }

    public function getAllOpponentTeamsGamesStatsCalculated(CalculationMethod $method): GameStatsCalculatedDTO
    {
        return $this->getAllGamesStatsCalculated(method: $method);
    }

    public function getAllPlayerGamesStatsCalculated(CalculationMethod $method, Player $player): GameStatsCalculatedDTO
    {
        return $this->getAllGamesStatsCalculated(method: $method, player: $player);
    }

    public function getAllGamesStatsCalculated(CalculationMethod $method, ?Player $player = null, ?Team $team = null): GameStatsCalculatedDTO
    {
        $attributes = [
            'minutes', 'points', 'fgm2', 'fga2', 'fgm3', 'fga3', 'ftm', 'fta',
            'offensiveRebound', 'defensiveRebound', 'foul', 'foulProvoked',
            'steal', 'turnover', 'assist', 'block', 'blockReceived',
            'efficiency', 'plusMinus'
        ];

        $calculatedStats = [];
        foreach ($attributes as $attribute) {
            $calculatedStats[$attribute] = 0;
        }

        $gamesPlayed = $this->getOnlyPlayedGames();

        $totalMinutes = array_sum($gamesPlayed->map(fn($gs) => $gs->getMinutes())->toArray());

        $nbGamesPlayed = count($gamesPlayed);
        if ($player === null) {
            $gamesList = [];
            foreach($gamesPlayed as $game) {
                if (!in_array($game->getGame()->getNumber(), $gamesList)) {
                    $gamesList[] = $game->getGame()->getNumber();
                }
            }
            $nbGamesPlayed = count($gamesList);
        }

        foreach ($attributes as $attribute) {
            $values = $gamesPlayed->map(fn($gs) => $gs->{'get' . ucfirst($attribute)}())->toArray();

            if ($method === CalculationMethod::SUM) {
                $calculatedStats[$attribute] += round(array_sum($values), 1);
            }
            elseif ($method === CalculationMethod::AVG) {
                $coeff = !$player ? $nbGamesPlayed : count($values);
                $calculatedStats[$attribute] += $coeff > 0 ? round(array_sum($values) / $coeff, 1) : 0;
            }
            elseif ($method === CalculationMethod::MINUTE) {
                $calculatedStats[$attribute] += $totalMinutes ? round(array_sum($values) / $totalMinutes * 40, 1) : 0;
            }
        }

        if ($player !== null) {
            $team = $player->getTeam();
        }

        return new GameStatsCalculatedDTO(
            $player,
            $team,
            $nbGamesPlayed,
            $calculatedStats['minutes'],
            $calculatedStats['points'],
            $calculatedStats['fgm2'],
            $calculatedStats['fga2'],
            $calculatedStats['fgm3'],
            $calculatedStats['fga3'],
            $calculatedStats['ftm'],
            $calculatedStats['fta'],
            $calculatedStats['offensiveRebound'],
            $calculatedStats['defensiveRebound'],
            $calculatedStats['foul'],
            $calculatedStats['foulProvoked'],
            $calculatedStats['steal'],
            $calculatedStats['turnover'],
            $calculatedStats['assist'],
            $calculatedStats['block'],
            $calculatedStats['blockReceived'],
            $calculatedStats['efficiency'],
            $calculatedStats['plusMinus']
        );
    }

}

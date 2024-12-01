<?php

namespace App\Collection;

use App\DTO\GameStatsCalculatedDTO;
use App\Entity\GameStats;
use App\Entity\Player;
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
        if ($this->allGamesStats->count()) {
            $this->allGamesStats = new ArrayCollection();
        }
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

    public function getAllGamesStatsCalculated(Player $player, CalculationMethod $method): GameStatsCalculatedDTO
    {
        $attributes = [
            'minutes', 'points', 'fgm2', 'fga2', 'fgm3', 'fga3', 'ftm', 'fta',
            'offensiveRebound', 'defensiveRebound', 'foul', 'foulProvoked',
            'steal', 'turnover', 'assist', 'block', 'blockReceived',
            'efficiency', 'plusMinus'
        ];

        $calculatedStats = [];

        foreach ($attributes as $attribute) {
            $values = $this->getOnlyPlayedGames()->map(fn($gs) => $gs->{'get' . ucfirst($attribute)}())->toArray();

            if ($method === CalculationMethod::SUM) {
                $calculatedStats[$attribute] = round(array_sum($values), 1);
            }
            elseif ($method === CalculationMethod::AVG) {
                $calculatedStats[$attribute] = count($values) > 0 ? round(array_sum($values) / count($values), 1) : 0;
            }
            elseif ($method === CalculationMethod::MINUTE) {
                $values = $this->getOnlyPlayedGames()->map(function($gs) use ($attribute) {
                    return $gs->{'get' . ucfirst($attribute)}() / $gs->getMinutes() * 40;
                })->toArray();
                $calculatedStats[$attribute] = count($values) > 0 ? round(array_sum($values) / count($values), 1) : 0;
            }
        }

        return new GameStatsCalculatedDTO(
            $player,
            $player->getTeam(),
            count($this->getOnlyPlayedGames()),
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
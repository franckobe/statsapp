<?php

namespace App\Collection;

use App\DTO\GameStatsCalculatedDTO;
use App\Entity\GameStats;
use App\Entity\Player;
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

    public function getAllGamesStatsCalculated(Player $player, string $method): GameStatsCalculatedDTO
    {
        $attributes = [
            'minutes', 'points', 'fgm2', 'fga2', 'fgm3', 'fga3', 'ftm', 'fta',
            'offensiveRebound', 'defensiveRebound', 'foul', 'foulProvoked',
            'steal', 'turnover', 'assist', 'block', 'blockReceived',
            'efficiency', 'plusMinus'
        ];

        $calculatedStats = [];

        foreach ($attributes as $attribute) {
            $values = $this->allGamesStats->map(fn($gs) => $gs->{'get' . ucfirst($attribute)}())->toArray();

            if ($method === 'SUM') {
                $calculatedStats[$attribute] = round(array_sum($values), 1);
            }
            elseif ($method === 'AVG') {
                $calculatedStats[$attribute] = count($values) > 0 ? round(array_sum($values) / count($values), 1) : 0;
            }
            elseif ($method === 'MINUTE') {
                $values = $this->allGamesStats->map(function($gs) use ($attribute) {
                    $minutes = $gs->getMinutes();
                    return $minutes === 0 ? 0 : $gs->{'get' . ucfirst($attribute)}() / $minutes * 40;
                })->toArray();
                $calculatedStats[$attribute] = count($values) > 0 ? round(array_sum($values) / count($values), 1) : 0;
            }
        }

        return new GameStatsCalculatedDTO(
            $player,
            $player->getTeam(),
            count($player->getGameStats()),
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
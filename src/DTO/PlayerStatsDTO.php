<?php

namespace App\DTO;

use App\Entity\Player;

class PlayerStatsDTO
{
    public float $fg2Percentage;
    public float $fg3Percentage;
    public float $fgmTot;
    public float $fgaTot;
    public float $fgTotPercentage;
    public float $ftPercentage;
    public float $rebound;
    public function __construct(
        public Player $player,
        public int $gamesPlayed,
        public float $minutes,
        public float $points,
        public float $fgm2,
        public float $fga2,
        public float $fgm3,
        public float $fga3,
        public float $ftm,
        public float $fta,
        public float $offensiveRebound,
        public float $defensiveRebound,
        public float $foul,
        public float $foulProvoked,
        public float $steal,
        public float $turnover,
        public float $assist,
        public float $block,
        public float $blockReceived,
        public float $efficiency,
        public float $plusMinus
    )
    {
        $this->fg2Percentage = round($fgm2/($fga2 == 0 ? 1 : $fga2)*100, 1);
        $this->fg3Percentage = round($fgm3/($fga3 == 0 ? 1 : $fga3)*100, 1);
        $this->ftPercentage = round($ftm/($fta == 0 ? 1 : $fta)*100, 1);

        $this->fgmTot = $fgm2 + $fgm3;
        $this->fgaTot = $fga2 + $fga3;
        $this->fgTotPercentage = round($this->fgmTot/($this->fgaTot == 0 ? 1 : $this->fgaTot)*100, 1);

        $this->rebound = $defensiveRebound + $offensiveRebound;
    }
}
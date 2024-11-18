<?php

namespace App\DTO;

use App\Entity\Player;

class PlayerAllStatsDTO
{
    public function __construct(
        public Player $player,
        public array $averages,
        public array $totals,
        public array $games,
    )
    {
    }
}
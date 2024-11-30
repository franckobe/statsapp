<?php

namespace App\DTO;

use App\Entity\Player;

class PlayerAllStatsDTO
{
    public function __construct(
        public Player                 $player,
        public GameStatsCalculatedDTO $averages,
        public GameStatsCalculatedDTO $totals,
        public GameStatsCalculatedDTO $averagesPer40min,
        public GameStatsCollectionDTO $games,
    )
    {
    }
}
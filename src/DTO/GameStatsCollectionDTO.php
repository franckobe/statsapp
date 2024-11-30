<?php

namespace App\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class GameStatsCollectionDTO extends ArrayCollection
{
    public static function fromCollection(Collection $gameStats): GameStatsCollectionDTO
    {
        $collection = new self();
        foreach ($gameStats as $gameStat) {
            $collection->add(new GameStatsDTO($gameStat));
        }
        return $collection;
    }
}
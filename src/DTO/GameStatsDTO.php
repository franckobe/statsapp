<?php

namespace App\DTO;

use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Common\Collections\Collection;

class GameStatsDTO
{
    public int $id;
    public Player $player;
    public Game $game;
    public bool $home;
    public Team $opponentTeam;
    public bool $win;
    public float $minutes;
    public int $points;
    public int $fgm2;
    public int $fga2;
    public int $fgm3;
    public int $fga3;
    public int $ftm;
    public int $fta;
    public int $offensiveRebound;
    public int $defensiveRebound;
    public int $foul;
    public int $foulProvoked;
    public int $steal;
    public int $turnover;
    public int $assist;
    public int $block;
    public int $blockReceived;
    public int $efficiency;
    public int $plusMinus;

    public float $fg2Percentage;
    public float $fg3Percentage;
    public float $fgmTot;
    public float $fgaTot;
    public float $fgTotPercentage;
    public float $ftPercentage;
    public float $rebound;

    public function __construct(GameStats $gameStats)
    {
        $this->id = $gameStats->getId();
        $this->player = $gameStats->getPlayer();
        $this->game = $gameStats->getGame();
        $this->home = $gameStats->getGame()->getHomeTeam()->getId() === $gameStats->getPlayer()->getTeam()->getId();
        $this->opponentTeam = $this->home ? $gameStats->getGame()->getAwayTeam() : $gameStats->getGame()->getHomeTeam();
        $this->win = ($this->home && $this->game->getHomeScore() > $this->game->getAwayScore()) || (!$this->home && $this->game->getHomeScore() < $this->game->getAwayScore());
        $this->minutes = round($gameStats->getMinutes(), 1);
        $this->points = $gameStats->getPoints();
        $this->fgm2 = $gameStats->getFgm2();
        $this->fga2 = $gameStats->getFga2();
        $this->fgm3 = $gameStats->getFgm3();
        $this->fga3 = $gameStats->getFga3();
        $this->ftm = $gameStats->getFtm();
        $this->fta = $gameStats->getFta();
        $this->offensiveRebound = $gameStats->getOffensiveRebound();
        $this->defensiveRebound = $gameStats->getDefensiveRebound();
        $this->foul = $gameStats->getFoul();
        $this->foulProvoked = $gameStats->getFoulProvoked();
        $this->steal = $gameStats->getSteal();
        $this->turnover = $gameStats->getTurnover();
        $this->assist = $gameStats->getAssist();
        $this->block = $gameStats->getBlock();
        $this->blockReceived = $gameStats->getBlockReceived();
        $this->efficiency = $gameStats->getEfficiency();
        $this->plusMinus = $gameStats->getPlusMinus();

        $this->fg2Percentage = round($this->fgm2/($this->fga2 == 0 ? 1 : $this->fga2)*100, 1);
        $this->fg3Percentage = round($this->fgm3/($this->fga3 == 0 ? 1 : $this->fga3)*100, 1);
        $this->ftPercentage = round($this->ftm/($this->fta == 0 ? 1 : $this->fta)*100, 1);

        $this->fgmTot = $this->fgm2 + $this->fgm3;
        $this->fgaTot = $this->fga2 + $this->fga3;
        $this->fgTotPercentage = round($this->fgmTot/($this->fgaTot == 0 ? 1 : $this->fgaTot)*100, 1);

        $this->rebound = $this->defensiveRebound + $this->offensiveRebound;
    }
}
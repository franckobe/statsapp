<?php

namespace App\Tool;

class GameStatsObject
{
    private string $playerName;
    private string $teamName;
    private float $minutes = 0;
    private int $points = 0;
    private int $fgm2 = 0;
    private int $fga2 = 0;
    private int $fgm3 = 0;
    private int $fga3 = 0;
    private int $ftm = 0;
    private int $fta = 0;
    private int $offensiveRebound = 0;
    private int $defensiveRebound = 0;
    private int $foul = 0;
    private int $foul_provoked = 0;
    private int $steal = 0;
    private int $turnover = 0;
    private int $assist = 0;
    private int $block = 0;
    private int $blockReceived = 0;
    private int $efficiency = 0;
    private int $plusMinus = 0;

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function setPlayerName(string $playerName): void
    {
        $this->playerName = $playerName;
    }

    public function getTeamName(): string
    {
        return $this->teamName;
    }

    public function setTeamName(string $teamName): void
    {
        $this->teamName = $teamName;
    }

    public function getMinutes(): float
    {
        return $this->minutes;
    }

    public function setMinutes(float $minutes): void
    {
        $this->minutes = $minutes;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getFgm2(): int
    {
        return $this->fgm2;
    }

    public function setFgm2(int $fgm2): void
    {
        $this->fgm2 = $fgm2;
    }

    public function getFga2(): int
    {
        return $this->fga2;
    }

    public function setFga2(int $fga2): void
    {
        $this->fga2 = $fga2;
    }

    public function getFgm3(): int
    {
        return $this->fgm3;
    }

    public function setFgm3(int $fgm3): void
    {
        $this->fgm3 = $fgm3;
    }

    public function getFga3(): int
    {
        return $this->fga3;
    }

    public function setFga3(int $fga3): void
    {
        $this->fga3 = $fga3;
    }

    public function getFtm(): int
    {
        return $this->ftm;
    }

    public function setFtm(int $ftm): void
    {
        $this->ftm = $ftm;
    }

    public function getFta(): int
    {
        return $this->fta;
    }

    public function setFta(int $fta): void
    {
        $this->fta = $fta;
    }

    public function getOffensiveRebound(): int
    {
        return $this->offensiveRebound;
    }

    public function setOffensiveRebound(int $offensiveRebound): void
    {
        $this->offensiveRebound = $offensiveRebound;
    }

    public function getDefensiveRebound(): int
    {
        return $this->defensiveRebound;
    }

    public function setDefensiveRebound(int $defensiveRebound): void
    {
        $this->defensiveRebound = $defensiveRebound;
    }

    public function getFoul(): int
    {
        return $this->foul;
    }

    public function setFoul(int $foul): void
    {
        $this->foul = $foul;
    }

    public function getFoulProvoked(): int
    {
        return $this->foul_provoked;
    }

    public function setFoulProvoked(int $foul_provoked): void
    {
        $this->foul_provoked = $foul_provoked;
    }

    public function getSteal(): int
    {
        return $this->steal;
    }

    public function setSteal(int $steal): void
    {
        $this->steal = $steal;
    }

    public function getTurnover(): int
    {
        return $this->turnover;
    }

    public function setTurnover(int $turnover): void
    {
        $this->turnover = $turnover;
    }

    public function getAssist(): int
    {
        return $this->assist;
    }

    public function setAssist(int $assist): void
    {
        $this->assist = $assist;
    }

    public function getBlock(): int
    {
        return $this->block;
    }

    public function setBlock(int $block): void
    {
        $this->block = $block;
    }

    public function getBlockReceived(): int
    {
        return $this->blockReceived;
    }

    public function setBlockReceived(int $blockReceived): void
    {
        $this->blockReceived = $blockReceived;
    }

    public function getEfficiency(): int
    {
        return $this->efficiency;
    }

    public function setEfficiency(int $efficiency): void
    {
        $this->efficiency = $efficiency;
    }

    public function getPlusMinus(): int
    {
        return $this->plusMinus;
    }

    public function setPlusMinus(int $plusMinus): void
    {
        $this->plusMinus = $plusMinus;
    }

}
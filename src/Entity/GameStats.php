<?php

namespace App\Entity;

use App\Repository\GameStatsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameStatsRepository::class)]
class GameStats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'gameStats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'gameStats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column]
    private ?float $minutes = null;

    #[ORM\Column]
    private ?int $points = null;

    #[ORM\Column]
    private ?int $fgm2 = null;

    #[ORM\Column]
    private ?int $fga2 = null;

    #[ORM\Column]
    private ?int $fgm3 = null;

    #[ORM\Column]
    private ?int $fga3 = null;

    #[ORM\Column]
    private ?int $ftm = null;

    #[ORM\Column]
    private ?int $fta = null;

    #[ORM\Column]
    private ?int $offensiveRebound = null;

    #[ORM\Column]
    private ?int $defensiveRebound = null;

    #[ORM\Column]
    private ?int $foul = null;

    #[ORM\Column]
    private ?int $foulProvoked = null;

    #[ORM\Column]
    private ?int $steal = null;

    #[ORM\Column]
    private ?int $turnover = null;

    #[ORM\Column]
    private ?int $assist = null;

    #[ORM\Column]
    private ?int $block = null;

    #[ORM\Column]
    private ?int $blockReceived = null;

    #[ORM\Column]
    private ?int $efficiency = null;

    #[ORM\Column]
    private ?int $plusMinus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getMinutes(): ?float
    {
        return $this->minutes;
    }

    public function setMinutes(float $minutes): static
    {
        $this->minutes = $minutes;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getFgm2(): ?int
    {
        return $this->fgm2;
    }

    public function setFgm2(int $fgm2): static
    {
        $this->fgm2 = $fgm2;

        return $this;
    }

    public function getFga2(): ?int
    {
        return $this->fga2;
    }

    public function setFga2(int $fga2): static
    {
        $this->fga2 = $fga2;

        return $this;
    }

    public function getFgm3(): ?int
    {
        return $this->fgm3;
    }

    public function setFgm3(int $fgm3): static
    {
        $this->fgm3 = $fgm3;

        return $this;
    }

    public function getFga3(): ?int
    {
        return $this->fga3;
    }

    public function setFga3(int $fga3): static
    {
        $this->fga3 = $fga3;

        return $this;
    }

    public function getFtm(): ?int
    {
        return $this->ftm;
    }

    public function setFtm(int $ftm): static
    {
        $this->ftm = $ftm;

        return $this;
    }

    public function getFta(): ?int
    {
        return $this->fta;
    }

    public function setFta(int $fta): static
    {
        $this->fta = $fta;

        return $this;
    }

    public function getOffensiveRebound(): ?int
    {
        return $this->offensiveRebound;
    }

    public function setOffensiveRebound(int $offensiveRebound): static
    {
        $this->offensiveRebound = $offensiveRebound;

        return $this;
    }

    public function getDefensiveRebound(): ?int
    {
        return $this->defensiveRebound;
    }

    public function setDefensiveRebound(int $defensiveRebound): static
    {
        $this->defensiveRebound = $defensiveRebound;

        return $this;
    }

    public function getFoul(): ?int
    {
        return $this->foul;
    }

    public function setFoul(int $foul): static
    {
        $this->foul = $foul;

        return $this;
    }

    public function getFoulProvoked(): ?int
    {
        return $this->foulProvoked;
    }

    public function setFoulProvoked(int $foulProvoked): static
    {
        $this->foulProvoked = $foulProvoked;

        return $this;
    }

    public function getSteal(): ?int
    {
        return $this->steal;
    }

    public function setSteal(int $steal): static
    {
        $this->steal = $steal;

        return $this;
    }

    public function getTurnover(): ?int
    {
        return $this->turnover;
    }

    public function setTurnover(int $turnover): static
    {
        $this->turnover = $turnover;

        return $this;
    }

    public function getAssist(): ?int
    {
        return $this->assist;
    }

    public function setAssist(int $assist): static
    {
        $this->assist = $assist;

        return $this;
    }

    public function getBlock(): ?int
    {
        return $this->block;
    }

    public function setBlock(int $block): static
    {
        $this->block = $block;

        return $this;
    }

    public function getBlockReceived(): ?int
    {
        return $this->blockReceived;
    }

    public function setBlockReceived(int $blockReceived): static
    {
        $this->blockReceived = $blockReceived;

        return $this;
    }

    public function getEfficiency(): ?int
    {
        return $this->efficiency;
    }

    public function setEfficiency(int $efficiency): static
    {
        $this->efficiency = $efficiency;

        return $this;
    }

    public function getPlusMinus(): ?int
    {
        return $this->plusMinus;
    }

    public function setPlusMinus(int $plusMinus): static
    {
        $this->plusMinus = $plusMinus;

        return $this;
    }
}

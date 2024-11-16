<?php

namespace App\Tool;

use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\Player;
use Spatie\PdfToText\Pdf;

class GameFileTool
{
    private string $fileContent;
    private string $homeTeam;
    private string $awayTeam;
    private int $gameNumber;
    /**
     * @var GameStatsObject[] $playersGameStats
     */
    private array $playersGameStats = [];
    private array $referees = [];

    public function __construct(string $filePath)
    {
        $this->fileContent = Pdf::getText($filePath, null, [
            'raw'
        ]);;

        $this->process();
    }

    private function process(): void
    {
        $this->fileContent = str_replace(array('Istres Sport BC', 'Istres Sports Basket Club'), 'Istres Sports BC', $this->fileContent);
        $this->fileContent = str_replace(array('Elias Agba Adouma', 'Ahmed Ibrahim'), array('Elias Agba Yadouma', 'Ibrahim Ahmed'), $this->fileContent);

        $lines = explode("\n", $this->fileContent);

        $homeFirstPlayerLine = 999999;
        $homeLastPlayerLine = 999999;
        $awayFirstPlayerLine = 999999;
        $awayLastPlayerLine = 999999;

        foreach ($lines as $i => $line) {
            // Extractions des équipes
            $regexTeams = '/([\p{L}\s]+)\s+(\d+)\s–\s(\d+)([\p{L}\s]+)/u';
            $regexReferees = '/^Crew Chief:([\p{L}\s]+)\s*Arbitre\(s\):([\p{L}\s]+)/u';
            $regexGameNumber = '/^MatchNo.:\s*(\d+)/';
            if (preg_match($regexTeams, $line, $matches)) {
                $this->homeTeam = trim($matches[1]);
                // $homeScore = intval(trim($matches[2]));
                // $awayScore = intval(trim($matches[3]));
                $this->awayTeam = trim($matches[4]);
            }
            elseif (preg_match($regexGameNumber, $line, $matches)) {
                $this->gameNumber = intval(trim($matches[1]));
            }
            elseif (preg_match($regexReferees, $line, $matches)) {
                $this->referees[] = trim($matches[1]);
                $this->referees[] = trim($matches[2]);
            }
            elseif (str_starts_with($line, "R/T")) {
                if ($homeFirstPlayerLine === 999999) {
                    $homeFirstPlayerLine = $i + 1;
                }
                else {
                    $awayFirstPlayerLine = $i + 1;
                }
            }
            elseif (str_starts_with($line, "Equipe/Coach")) {
                if ($homeLastPlayerLine === 999999) {
                    $homeLastPlayerLine = $i;
                }
                else {
                    $awayLastPlayerLine = $i;
                }
            }
        }

        foreach ($lines as $i => $line) {
            if ($i >= $homeFirstPlayerLine && $i < $homeLastPlayerLine || $i >= $awayFirstPlayerLine && $i < $awayLastPlayerLine) {
                $currentTeam = $this->homeTeam;
                if ($i >= $awayFirstPlayerLine && $i < $awayLastPlayerLine) {
                    $currentTeam = $this->awayTeam;
                }

                $line = str_replace(['(C)','*'], '', $line);
                $line = preg_replace('/(\p{L})\s(\p{L})\s/u', '${1}${2}', $line);
                $line = preg_replace('/(\p{Ll})(\p{Lu})/u', '${1} ${2}', $line);

                $gameStatsObject = new GameStatsObject();

                $regex1 = '/^(\d+)\s+([\p{L}\s]+)(\d+:\d+|NPJ)/u';
                preg_match($regex1, $line, $matches);

                if (!isset($matches[3])) {
                    dd($line);
                }

                $minutes = 0;
                if (trim($matches[3]) !== 'NPJ') {
                    $minutesExploded = explode(':', trim($matches[3]));
                    $seconds = intval($minutesExploded[1]) / 60;
                    $minutes = intval($minutesExploded[0]) + $seconds;
                }

                $gameStatsObject->setPlayerName(trim($matches[2]));
                $gameStatsObject->setMinutes($minutes);
                $gameStatsObject->setTeamName($currentTeam);


                if (!str_contains($line, 'NPJ')) {
                    $line = trim(preg_replace($regex1, '', $line));
                    $exploded = preg_split('/\s+/', $line);
                    $gameStatsObject->setPoints(intval($exploded[0]));
                    $gameStatsObject->setFgm2(intval(explode('/', trim($exploded[1]))[0]));
                    $gameStatsObject->setFga2(intval(explode('/', trim($exploded[1]))[1]));
                    $gameStatsObject->setFgm3(intval(explode('/', $exploded[4])[0]));
                    $gameStatsObject->setFga3(intval(explode('/', $exploded[4])[1]));
                    $gameStatsObject->setFtm(intval(explode('/', $exploded[8])[0]));
                    $gameStatsObject->setFta(intval(explode('/', $exploded[8])[1]));
                    $gameStatsObject->setOffensiveRebound(intval($exploded[10]));
                    $gameStatsObject->setDefensiveRebound(intval($exploded[11]));
                    $gameStatsObject->setFoul(intval($exploded[13]));
                    $gameStatsObject->setFoulProvoked(intval($exploded[14]));
                    $gameStatsObject->setSteal(intval($exploded[15]));
                    $gameStatsObject->setTurnover(intval($exploded[16]));
                    $gameStatsObject->setAssist(intval($exploded[17]));
                    $gameStatsObject->setBlock(intval($exploded[18]));
                    $gameStatsObject->setBlockReceived(intval($exploded[19]));
                    $gameStatsObject->setEfficiency(intval($exploded[20]));
                    $gameStatsObject->setPlusMinus(intval($exploded[23]));
                }

                $this->playersGameStats[] = $gameStatsObject;
            }
        }
    }

    public static function convertGameStatObjectToEntity(Game $game, Player $player, GameStatsObject $gameStatsObject): GameStats {
        $gameStats = new GameStats();
        $gameStats->setGame($game);
        $gameStats->setPlayer($player);
        $gameStats->setMinutes($gameStatsObject->getMinutes());
        $gameStats->setPoints($gameStatsObject->getPoints());
        $gameStats->setFgm2($gameStatsObject->getFgm2());
        $gameStats->setFga2($gameStatsObject->getFga2());
        $gameStats->setFgm3($gameStatsObject->getFgm3());
        $gameStats->setFga3($gameStatsObject->getFga3());
        $gameStats->setFtm($gameStatsObject->getFtm());
        $gameStats->setFta($gameStatsObject->getFta());
        $gameStats->setOffensiveRebound($gameStatsObject->getOffensiveRebound());
        $gameStats->setDefensiveRebound($gameStatsObject->getDefensiveRebound());
        $gameStats->setFoul($gameStatsObject->getFoul());
        $gameStats->setFoulProvoked($gameStatsObject->getFoulProvoked());
        $gameStats->setSteal($gameStatsObject->getSteal());
        $gameStats->setTurnover($gameStatsObject->getTurnover());
        $gameStats->setAssist($gameStatsObject->getAssist());
        $gameStats->setBlock($gameStatsObject->getBlock());
        $gameStats->setBlockReceived($gameStatsObject->getBlockReceived());
        $gameStats->setEfficiency($gameStatsObject->getEfficiency());
        $gameStats->setPlusMinus($gameStatsObject->getPlusMinus());
        return $gameStats;
    }

    public function getHomeTeam(): string
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): string
    {
        return $this->awayTeam;
    }

    public function getGameNumber(): int
    {
        return $this->gameNumber;
    }

    public function getPlayersGameStats(): array
    {
        return $this->playersGameStats;
    }

    public function getReferees(): array
    {
        return $this->referees;
    }

}
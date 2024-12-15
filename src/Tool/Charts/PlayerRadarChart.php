<?php

namespace App\Tool\Charts;

use App\DTO\GameStatsCalculatedDTO;
use App\Entity\Player;
use App\Enum\StatsChartType;

class PlayerRadarChart extends StatsChart
{
    public function __construct(Player $player, GameStatsCalculatedDTO $playerAverages)
    {
        parent::__construct(StatsChartType::RADAR);

        $this->setData([
            'labels' => [
                'Points',
                'Rebonds',
                'Passes déc.',
                'Interceptions',
                'Contres'
            ],
            'datasets' => [
                [
                    'label' => $player->getName(),
                    'data' => [
                        $playerAverages->points,
                        $playerAverages->rebound,
                        $playerAverages->assist,
                        $playerAverages->steal,
                        $playerAverages->block
                    ],
                    'backgroundColor' => 'rgb(88 28 135 / 70%)',
                ],
            ]
        ]);

        //$this->defaultOptions['scales']['r']['suggestedMax'] = $playerAverages->points;
        $this->setOptions($this->defaultOptions);
    }

}

<?php

namespace App\Tool\Charts;

use App\DTO\GameStatsCollectionDTO;
use App\Entity\Player;
use App\Enum\StatsChartType;

class PlayerGamesChart extends StatsChart
{
    public function __construct(GameStatsCollectionDTO $playerGamesStats)
    {
        parent::__construct(StatsChartType::LINE);

        $labels = [];
        $allData = [
            'MIN' => [],
            'PTS' => [],
            'REB' => [],
            'PD' => [],
            'IN' => [],
            'CTR' => [],
        ];

        foreach ($playerGamesStats as $gameStats) {
            $labels[] = 'J' . $gameStats->game->getNumber();
            $allData['MIN'][] = $gameStats->minutes;
            $allData['PTS'][] = $gameStats->points;
            $allData['REB'][] = $gameStats->rebound;
            $allData['PD'][] = $gameStats->assist;
            $allData['IN'][] = $gameStats->steal;
            $allData['CTR'][] = $gameStats->block;
        }

        $dataSets = [];
        foreach ($allData as $label => $data) {
            $dataSets[] = [
                'label' => $label,
                'data' => $data,
                'hidden' => $label !== 'PTS',
            ];
        }

        $this->setData([
            'labels' => $labels,
            'datasets' => $dataSets
        ]);

        $this->setOptions($this->defaultOptions);
    }
}

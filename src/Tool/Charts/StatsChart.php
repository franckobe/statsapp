<?php

namespace App\Tool\Charts;

use App\Enum\StatsChartType;
use Symfony\UX\Chartjs\Model\Chart;

abstract class StatsChart extends Chart
{
    protected array $defaultOptions = [
        'plugins' => [
            'legend' => [
                'labels' => [
                    'color' => 'white',
                ]
            ]
        ],
        'scales' => [
            "x" => [
                "grid" => [
                    "color" => "rgba(200, 129, 255, 0.3)"
                ],
                "ticks" => [
                    "color" => "white"
                ]
            ],
            "y" => [
                "grid" => [
                    "color" => "rgba(200, 129, 255, 0.3)"
                ],
                "ticks" => [
                    "color" => "white"
                ]
            ],
            'r' => [
                'grid' => [
                    'color' => 'rgb(200 129 255 / 30%)',
                ],
                'angleLines' => [
                    'color' => 'rgb(200 129 255 / 15%)',
                ],
                'pointLabels' => [
                    'color' => 'white',
                ],
            ],
        ],
    ];

    public function __construct(private readonly StatsChartType $chartType)
    {
        parent::__construct($this->chartType->value);

        if ($this->chartType !== StatsChartType::RADAR) {
            unset($this->defaultOptions['scales']['r']);
        }
        else {
            unset($this->defaultOptions['scales']['x']);
            unset($this->defaultOptions['scales']['y']);
        }
    }

}

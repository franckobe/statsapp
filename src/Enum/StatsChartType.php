<?php

namespace App\Enum;

use Symfony\UX\Chartjs\Model\Chart;

enum StatsChartType: string
{
    case RADAR = Chart::TYPE_RADAR;
    case BAR = Chart::TYPE_BAR;
    case PIE = Chart::TYPE_PIE;
    case LINE = Chart::TYPE_LINE;
    case BUBBLE = Chart::TYPE_BUBBLE;
    case DOUGHNUT = Chart::TYPE_DOUGHNUT;
    case POLAR_AREA = Chart::TYPE_POLAR_AREA;
    case SCATTER = Chart::TYPE_SCATTER;
}

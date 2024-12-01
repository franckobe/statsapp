<?php

namespace App\Enum;

enum CalculationMethod: string
{
    case AVG = 'Moyenne';
    case SUM = 'Total';
    case MINUTE = 'Moy. /40min';
}

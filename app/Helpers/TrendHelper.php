<?php

namespace App\Helpers;

class TrendHelper
{
    /**
     * Calculate trend between a current value and a comparison value.
     * 
     * @param float $currentValue
     * @param float $compareValue
     * @param string $format 'percentage' or 'absolute'
     * @param bool $inverse If true, higher is worse (red), lower is better (green)
     * @return array ['color', 'icon', 'value']
     */
    public static function calculate($currentValue, $compareValue, $format = 'percentage', $inverse = false)
    {
        $currentValue = (float) $currentValue;
        $compareValue = (float) $compareValue;

        $diff = $currentValue - $compareValue;
        
        $color = 'text-slate-500';
        $icon = '-';
        $valueStr = '0.0%';

        if ($diff > 0) {
            $color = $inverse ? 'text-red-500' : 'text-emerald-500';
            $icon = '▲';
        } elseif ($diff < 0) {
            $color = $inverse ? 'text-emerald-500' : 'text-red-500';
            $icon = '▼';
        }

        if ($format === 'absolute') {
            // Check if the numbers are large enough to not need decimals
            $isFloat = (floor($currentValue) != $currentValue) || (floor($compareValue) != $compareValue);
            
            $valueStr = ($diff > 0 ? '+' : '') . number_format($diff, $isFloat ? 1 : 0);
            if ($valueStr === '+0' || $valueStr === '-0' || $valueStr === '+0.0' || $valueStr === '-0.0') {
                $valueStr = '0';
            }
        } else {
            if ($compareValue > 0) {
                $pct = abs($diff) / $compareValue * 100;
                $valueStr = number_format($pct, 1) . '%';
            } else {
                if ($currentValue > 0) {
                    $valueStr = '100%';
                } else {
                    $valueStr = '0%';
                }
            }
        }

        if ($diff == 0) {
            $color = 'text-slate-500';
            $icon = '-';
            if ($format === 'absolute') {
                $valueStr = '0';
            } else {
                $valueStr = '0%';
            }
        }

        return [
            'color' => $color,
            'icon' => $icon,
            'value' => $valueStr
        ];
    }
}

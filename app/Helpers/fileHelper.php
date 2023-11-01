<?php

namespace App\Helpers;

class fileHelper
{
    public static function formatBits($bits, $precision = 1)
    {
        $units = [' B', ' KiB', ' MiB', ' GiB', ' TiB'];

        $bits = max($bits, 0);
        $pow = floor(($bits ? log($bits) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bits /= (1 << (10 * $pow));

        return round($bits, $precision) . $units[$pow];
    }
}
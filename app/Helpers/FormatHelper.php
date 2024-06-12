<?php

namespace App\Helpers;

class FormatHelper
{
    public static function formatMemory($memory): string
    {
        if (is_null($memory)) {
            $memory = memory_get_peak_usage();
        }

        return round($memory / pow(1024, ($i = floor(log($memory, 1024)))), 2) . ' ' . self::memoryUnits()[$i];
    }

    private static function memoryUnits(): array
    {
        return ['B', 'KB', 'MB', 'GB', 'TB'];
    }
}

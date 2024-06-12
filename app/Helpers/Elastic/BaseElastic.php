<?php

namespace App\Helpers\Elastic;

abstract class BaseElastic
{
    abstract public static function index(): string;
    abstract public static function mapping(): array;

    public static function settings(): array
    {
        return [];
    }

    public static function suggested(): array
    {
        return [];
    }
}

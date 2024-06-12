<?php

namespace App\Helpers\Elastic;

class AirportElastic extends BaseElastic
{
    public static function index(): string
    {
        return 'airports';
    }

    public static function mapping(): array
    {
        return [
            'properties' => [
                'id' => [
                    'type' => 'integer'
                ],
                'code' => [
                    'type' => 'text',
                ],
                'city_name_ru' => [
                    'type' => 'text',
                ],
                'city_name_en' => [
                    'type' => 'text',
                ],
                'name_ru' => [
                    'type' => 'text',
                ],
                'name_en' => [
                    'type' => 'text',
                ],
                'latitude' => [
                    'type' => 'float',
                ],
                'longitude' => [
                    'type' => 'float',
                ],
                'suggest' => [
                    'type' => 'completion'
                ],
            ]
        ];
    }

    public static function suggested(): array
    {
        return [
            'city_name_ru',
            'city_name_en',
            'name_ru',
            'name_en',
        ];
    }
}

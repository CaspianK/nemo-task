<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AirportControllerTest extends TestCase
{
    /**
     * Проверка структуры ответа при поиске аэропортов
     */
    public function test_airports_search_structure(): void
    {
        $response = $this->get('/api/search?query=ast');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'code',
                    'city_name_ru',
                    'city_name_en',
                    'name_ru',
                    'name_en',
                    'latitude',
                    'longitude'
                ]
            ]);
    }

    public function test_airports_search_autocomplete(): void
    {
        $response = $this->get('/api/search?query=ist');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'city_name_ru' => 'Стамбул',
                'city_name_en' => 'Istanbul'
            ]);
    }

    public function test_airports_search_empty(): void
    {
        $response = $this->get('/api/search?query=zzz');

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function test_airports_search_validation(): void
    {
        $response = $this->get('/api/search', [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['query']);
    }
}

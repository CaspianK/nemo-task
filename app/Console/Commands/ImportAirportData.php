<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Country;
use App\Models\Timezone;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class ImportAirportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:airport-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Airport Data from a JSON file.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $client = new Client([
            'timeout' => 10,
        ]);

        try {
            $response = $client->request('GET', 'https://raw.githubusercontent.com/NemoTravel/nemo.travel.geodata/master/airports.json');

            $airports = json_decode($response->getBody()->getContents(), true);
            $totalCount = count($airports);
            $currentCount = 0;

            foreach ($airports as $code => $data) {
                $country = Country::firstOrCreate([
                    'code' => $data['country']
                ]);
                $timezone = Timezone::firstOrCreate([
                    'name' => $data['timezone']
                ]);
                $city = City::firstOrCreate(
                    [
                        'country_id' => $country->id,
                        'name_ru' => $data['cityName']['ru'],
                        'name_en' => $data['cityName']['en']
                    ],
                    [
                        'timezone_id' => $timezone->id
                    ]
                );

                $airport = $city->airports()->updateOrCreate(
                    ['code' => $code],
                    [
                        'latitude' => $data['lat'] ?? null,
                        'longitude' => $data['lng'] ?? null
                    ]
                );

                if (array_key_exists('airportName', $data)) {
                    $airport->name()->updateOrCreate(
                        ['airport_id' => $airport->id],
                        [
                            'name_ru' => $data['airportName']['ru'] ?? null,
                            'name_en' => $data['airportName']['en'] ?? null
                        ]
                    );
                }

                $this->info("Imported airport {$code}. Current progress: " . ++$currentCount . "/{$totalCount}.");
            }
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());
            return;
        }
    }
}

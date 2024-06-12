<?php

namespace App\Console\Commands\Elastic;

use App\Helpers\Elastic\AirportElastic;
use App\Helpers\FormatHelper;
use App\Models\Airport;
use App\Services\ElasticService;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class IndexAirports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:index-airports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index airports.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $this->info('Indexing estates...');
        $this->info(date('Y-m-d H:i:s'));
        $this->info('----------------------------------------');

        $airportData = DB::table('airports')
            ->select(
                'airports.id',
                'airports.code',
                'cities.name_ru as city_name_ru',
                'cities.name_en as city_name_en',
                'airport_names.name_ru',
                'airport_names.name_en',
                'airports.latitude',
                'airports.longitude'
            )
            ->leftJoin('cities', 'airports.city_id', '=', 'cities.id')
            ->leftJoin('airport_names', 'airports.id', '=', 'airport_names.airport_id')
            ->get()
            ->toArray();

        try {
            app(ElasticService::class)->createIndex(AirportElastic::index(), AirportElastic::mapping(), $airportData, AirportElastic::settings(), AirportElastic::suggested());
        } catch (ClientResponseException $e) {
            $this->error('Elasticsearch client response error. ' . $e->getMessage());
            return;
        } catch (MissingParameterException $e) {
            $this->error('Elasticsearch missing parameter error. ' . $e->getMessage());
        } catch (ServerResponseException $e) {
            $this->error('Elasticsearch server response error. ' . $e->getMessage());
            return;
        } catch (\Exception $e) {
            $this->error('Elasticsearch error. ' . $e->getMessage());
            return;
        }

        $endTime = microtime(true);
        $endMemory = memory_get_peak_usage();
        $totalTime = number_format($endTime - $startTime, 2);
        $totalMemory = $endMemory - $startMemory;

        $this->info('----------------------------------------');
        $this->info('Total time: ' . $totalTime . ' seconds');
        $this->info('Total memory: ' . FormatHelper::formatMemory($totalMemory));
    }
}

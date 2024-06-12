<?php

namespace App\Traits;

use App\Services\ElasticService;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Http\Promise\Promise;

trait Searchable
{
    public static function suggest(string $index, string $query): array
    {
        return app(ElasticService::class)->suggest($index, $query);
    }
}

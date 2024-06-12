<?php

return [
    'host' => [
        env('ELASTICSEARCH_HOST', 'localhost') . ':' . env('ELASTICSEARCH_PORT', 9200)
    ],
    'user' => env('ELASTICSEARCH_USERNAME'),
    'pass' => env('ELASTICSEARCH_PASSWORD'),
];

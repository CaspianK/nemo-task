<?php

namespace App\Services;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;

class ElasticService
{
    protected Client $client;

    /**
     * @throws AuthenticationException
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(config('elasticsearch.host'))
            ->setBasicAuthentication(config('elasticsearch.user'), config('elasticsearch.pass'))
            ->build();
    }

    /**
     * @throws ClientResponseException,
     * @throws MissingParameterException,
     * @throws ServerResponseException
     * @throws \Exception
     */
    public function createIndex(string $index, array $mapping, array $data, array $settings, array $suggested): void
    {
        $indexName = $index . '_' . date('Y_m_d_H_i_s');

        $params = [
            'index' => $indexName,
            'body' => [
                'mappings' => $mapping,
            ],
        ];

        if (!empty($settings)) {
            $params['body']['settings'] = $settings;
        }

        $response = $this->client->indices()->create($params);

        if ($response['acknowledged']) {
            $bulk = [];

            foreach ($data as $item) {
                $bulk['body'][] = [
                    'index' => [
                        '_index' => $indexName,
                        '_id' => $item->id
                    ]
                ];

                if (!empty($suggested)) {
                    $inputArray = [];
                    foreach ($item as $key => $value) {
                        if (in_array($key, $suggested) && !empty($value)) {
                            $inputArray[] = $value;
                        }
                    }
                    $item->suggest = ['input' => $inputArray];
                }

                $bulk['body'][] = $item;
            }

            if ($bulk) {
                $response = $this->client->bulk($bulk);
            }

            $errors = [];

            if ($response['errors']) {
                foreach ($response['items'] as $item) {
                    if (isset($item['index']['error'])) {
                        $errors[] = $item['index']['error'];
                    }
                }
            }

            if ($errors) {
                $this->client->indices()->delete(['index' => $indexName]);
                throw new \Exception('Error while indexing data: ' . json_encode($errors));
            }

            if ($this->client->indices()->existsAlias(['name' => $index])->asBool()) {
                $currentAliases = $this->client->indices()->getAlias(['name' => $index])->asArray();

                if (!empty($currentAliases)) {
                    foreach ($currentAliases as $oldIndex => $alias) {
                        $this->client->indices()->updateAliases([
                            'body' => [
                                'actions' => [
                                    [
                                        'remove' => [
                                            'index' => $oldIndex,
                                            'alias' => $index
                                        ]
                                    ]
                                ]
                            ]
                        ]);

                        $params = [
                            'index' => $oldIndex
                        ];

                        $this->client->indices()->delete($params);
                    }
                }
            }

            $this->client->indices()->updateAliases([
                'body' => [
                    'actions' => [
                        [
                            'add' => [
                                'index' => $indexName,
                                'alias' => $index
                            ]
                        ]
                    ]
                ]
            ]);
        }
    }

    private function search(string $index, array $searchParams): array
    {
        $params = [
            'index' => $index,
            'body' => $searchParams
        ];

        return $this->client->search($params)->asArray();
    }

    public function suggest(string $index, string $query): array
    {
        $params = [
            'suggest' => [
                'suggest' => [
                    'prefix' => $query,
                    'completion' => [
                        'field' => 'suggest'
                    ],
                ],
            ],
        ];

        $result = $this->search($index, $params);
        $suggestions = $result['suggest']['suggest'][0]['options'] ?? [];
        return array_map(function($suggestion) {
            $source = $suggestion['_source'];
            unset($source['suggest']);
            return $source;
        }, $suggestions);
    }
}

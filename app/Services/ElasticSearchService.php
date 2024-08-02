<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticSearchService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(config('elasticsearch.hosts'))
            ->setBasicAuthentication(config('elasticsearch.username'), config('elasticsearch.password'))
            ->build();
    }

    public function createIndex($params)
    {
        return $this->client->indices()->create($params);
    }

    public function indexExists($params)
    {
        return $this->client->indices()->exists($params);
    }

    public function index($index, $id, $body)
    {
        return $this->client->index([
            'index' => $index,
            'id'    => $id,
            'body'  => $body,
        ]);
    }

    public function search($index, $query)
    {
        return $this->client->search([
            'index' => $index,
            'body'  => $query,
        ]);
    }

    public function delete($index, $id)
    {
        return $this->client->delete([
            'index' => $index,
            'id'    => $id,
        ]);
    }
}
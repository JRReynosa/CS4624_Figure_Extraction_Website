<?php

use Elasticsearch\ClientBuilder;

function text_query($client, $search)
{
    $query = $client->search([
        'index' => 'figures',
        'size' => 10000,
        'body' => [
            'query' => [
                'bool' => [
                    'should' => [
                        ['match' => ['caption' => $search]],
                        ['match' => ['figType' => $search]],
                        ['match' => ['imageText' => $search]],
                    ]
                ]
            ]
        ]
    ]);

    return $query;
}

function normal_search($search)
{
    include '../../constants.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    //original
    //$client = Elasticsearch\ClientBuilder::create()->setHosts(ELASTICSEARCH_HOST)->build();
    $client = Elasticsearch\ClientBuilder::create()->build();

    $query = [];

    $query = text_query($client, $search);

    if ($query['hits']['total'] >= 1) {
        $results = $query['hits']['hits'];
    }

    return $results;
}

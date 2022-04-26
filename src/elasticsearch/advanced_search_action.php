<?php

require '../../vendor/autoload.php';

function no_inputs($title, $author, $abstract, $publisher, $subject, $department, $degree, $beg_date, $end_date)
{
    $all_empty = true;

    // If any of the inputs are filled in, set the all empty boolean
    // to false.

    if (!empty($title)) {
        $all_empty = false;
    }

    if (!empty($author)) {
        $all_empty = false;
    }

    if (!empty($abstract)) {
        $all_empty = false;
    }

    if (!empty($publisher)) {
        $all_empty = false;
    }

    if (!empty($subject)) {
        $all_empty = false;
    }

    if (!empty($department)) {
        $all_empty = false;
    }

    if (!empty($degree)) {
        $all_empty = false;
    }

    if (!empty($beg_date)) {
        if (!empty($end_date)) {
            $all_empty = false;
        } else {
            echo "<script> alert('Both date ranges must be filled.');
            window.location = '../../src/elasticsearch/results.php'; </script>";
        }
    }

    if (!empty($end_date)) {
        if (!empty($beg_date)) {
            $all_empty = false;
        } else {
            echo "<script> alert('Both date ranges must be filled.');
            window.location = '../../src/elasticsearch/results.php'; </script>";
        }
    }

    return $all_empty;
}

function query_builder($query_array, $field, $input, $input_2)
{
    if ($field == 'date_issued') {
        $date_query =                      [
            'range' => [
                $field => [
                    'gte' => $input,
                    'lte' => $input_2
                ]
            ]
        ];

        array_push($query_array['body']['query']['bool']['must'], $date_query);
    } else {
        array_push($query_array['body']['query']['bool']['must'], ['match' => [$field => $input]]);
    }
    return $query_array;
}

function advanced_search($title, $author, $abstract, $publisher, $subject, $department, $degree, $beg_date, $end_date)
{
    include '../../constants.php';
    
    $client = Elasticsearch\ClientBuilder::create()->setHosts(ELASTICSEARCH_HOST)->build();

    // Initialize a string builder for the advanced search query.
    $query_array = [
        'index' => 'dissertations',
        'size' => 10000,
        'body' => [
            'query' => [
                'bool' => [
                    'must' => []
                ]
            ]
        ]
    ];


    if (!empty($title)) {
        $query_array = query_builder($query_array, 'title', $title, 0);
    }

    if (!empty($author)) {
        $query_array = query_builder($query_array, 'contributor_author', $author, 0);
    }

    if (!empty($abstract)) {
        $query_array = query_builder($query_array, 'description_abstract', $abstract, 0);
    }

    if (!empty($publisher)) {
        $query_array = query_builder($query_array, 'publisher', $publisher, 0);
    }

    if (!empty($subject)) {
        $query_array = query_builder($query_array, 'subject', $subject, 0);
    }

    if (!empty($department)) {
        $query_array = query_builder($query_array, 'contributor_department', $department, 0);
    }

    if (!empty($degree)) {
        $query_array = query_builder($query_array, 'description_degree', $degree, 0);
    }

    if (!empty($beg_date) || !empty($end_date)) {
        // If the beginning date or end date are not empty,
        // prepare for concatenating the date range search
        // in the string builder.

        if (!DateTime::createFromFormat('Y-m-d', $beg_date) || !DateTime::createFromFormat('Y-m-d', $end_date)) {
            echo "<script> alert('One or both of the date ranges were in an incorrect format.');
            window.location = '../../public/views/results.php'; </script>";
        }

        if (empty($beg_date)) {
            // If the beginning date is empty, set it to January 1, 1980.
            $beg_date = date("1980-01-01");
        }

        if (empty($end_date)) {
            // If the ending date is empty, set it to today's date.
            $end_date = date("Y-m-d");
        }

        $query_array = query_builder($query_array, 'date_issued', $beg_date, $end_date);
    }

    $query = $client->search($query_array);


    if ($query['hits']['total'] >= 1) {
        $results = $query['hits']['hits'];
    }

    return $results;
}

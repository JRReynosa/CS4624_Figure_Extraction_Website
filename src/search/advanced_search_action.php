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

function query_builder($query, $field, $value, $param_added)
{
    if ($param_added) {
        $query = $query . " AND";
    } else {
        $param_added = true;
    }

    $query = $query . " pates_etds.etds.$field LIKE '%$value%'";

    return [$query, $param_added];
}

function advanced_search($title, $author, $abstract, $publisher, $subject, $department, $degree, $beg_year, $end_year)
{
    include '../mysql_login.php';

    $query = "SELECT * FROM pates_etds.etds WHERE";
    $param_added = false;
    
    if (!empty($title)) {
        $values = query_builder($query, 'title', $title, $param_added);
        $query = $values[0];
        $param_added = $values[1];
    }

    if (!empty($author)) {
        $values = query_builder($query, 'author', $author, $param_added);
        $query = $values[0];
        $param_added = $values[1];
    }

    if (!empty($abstract)) {
        $values = query_builder($query, 'abstract', $abstract, $param_added);
        $query = $values[0];
        $param_added = $values[1];
    }

    if (!empty($publisher)) {
        $values = query_builder($query, 'university', $patent_id, $param_added);
        $query = $values[0];
        $param_added = $values[1];
    }

    if (!empty($subject)) {
        $values = query_builder($query, 'subject', $patent_id, $param_added);
        $query = $values[0];
        $param_added = $values[1];
    }

    if (!empty($department)) {
        $values = query_builder($query, 'department', $patent_id, $param_added);
        $query = $values[0];
        $param_added = $values[1];
    }

    if (!empty($degree)) {
        $values = query_builder($query, 'degree', $patent_id, $param_added);
        $query = $values[0];
        $param_added = $values[1];
    }

    if (!empty($beg_year) && !empty($end_year)) {
        $query = $query . " pates_etds.etds.year BETWEEN '$beg_year' AND '$end_year'";
    }


    $query = $query . ";";

    return  $connection->query($query);
}

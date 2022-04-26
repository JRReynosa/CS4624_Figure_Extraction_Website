<?php

require_once '../../src/elasticsearch/normal_search.php';
require_once '../../src/elasticsearch/advanced_search_action.php';
require_once '../../src/dissertation.php';

$dissertation_jsons = [];
$results = [];

if (isset($_GET['search'])) {

    $search = $_GET['search'];

    $search = filter_var($search, FILTER_SANITIZE_STRING);

    $results = normal_search($search);
}

if (
    isset($_GET['title']) || isset($_GET['author']) || isset($_GET['abstract'])
    || isset($_GET['publisher']) || isset($_GET['subject']) || isset($_GET['department'])
    || isset($_GET['degree']) || isset($_GET['start_date']) || isset($_GET['end_date'])
) {

    $title = $author = $abstract = $publisher = $subject = $department =
        $degree = $beg_date = $end_date = "";

    if (isset($_GET['title'])) {
        $title = $_GET['title'];
        $title = filter_var($title, FILTER_SANITIZE_STRING);
    }

    if (isset($_GET['author'])) {
        $author = $_GET['author'];
        $author = filter_var($author, FILTER_SANITIZE_STRING);
    }

    if (isset($_GET['abstract'])) {
        $abstract = $_GET['abstract'];
        $abstract = filter_var($abstract, FILTER_SANITIZE_STRING);
    }

    if (isset($_GET['publisher'])) {
        $publisher = $_GET['publisher'];
        $publisher = filter_var($publisher, FILTER_SANITIZE_STRING);
    }

    if (isset($_GET['subject'])) {
        $subject = $_GET['subject'];
        $subject = filter_var($subject, FILTER_SANITIZE_STRING);
    }

    if (isset($_GET['department'])) {
        $department = $_GET['department'];
        $department = filter_var($department, FILTER_SANITIZE_STRING);
    }

    if (isset($_GET['degree'])) {
        $degree = $_GET['degree'];
        $degree = filter_var($degree, FILTER_SANITIZE_STRING);
    }

    if (isset($_GET['start_date'])) {
        $beg_date = $_GET['start_date'];
        $beg_date = filter_var($beg_date, FILTER_SANITIZE_STRING);
    }

    if (isset($_GET['end_date'])) {
        $end_date = $_GET['end_date'];
        $end_date = filter_var($end_date, FILTER_SANITIZE_STRING);
    }



    $results = advanced_search(
        $title,
        $author,
        $abstract,
        $publisher,
        $subject,
        $department,
        $degree,
        $beg_date,
        $end_date
    );
}

foreach ($results as $r) {
    $dissertation = [];
    $dissertation['title'] = $r['_source']['title'];
    $dissertation['author'] = $r['_source']['contributor_author'];
    $dissertation['publisher'] = $r['_source']['publisher'];
    $dissertation['url'] = $r['_source']['identifier_sourceurl'];
    $dissertation['date'] = $r['_source']['date_issued'];
    $dissertation['abstract'] = $r['_source']['description_abstract'];

    array_push($dissertation_jsons, json_encode($dissertation));
}

foreach($dissertation_jsons as $j) {
    echo $j . PHP_EOL;
}

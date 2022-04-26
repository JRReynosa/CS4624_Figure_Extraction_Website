<?php

require '../../vendor/autoload.php';
include '../../constants.php';
require '../mysql_login.php';

if (isset($_POST['text'])) {

    $text = $_POST['text'];

    $search = $_POST['text'];

    $query = "SELECT * FROM pates_etds.etds WHERE title LIKE '%$search%' 
    OR author LIKE '%author%' OR advisor LIKE '%$search%'
    OR abstract LIKE '%$search%' OR university LIKE '%$search%' 
    OR degree LIKE '%$search%' OR URI LIKE '%$search%' 
    OR discipline LIKE '%$search%' OR language LIKE '%$search%' LIMIT 10;";

    $results = $connection->query($query);

    $suggestions = [];

    while ($row = $results->fetch_assoc()) {
        if (strpos($row['title'], $search) !== false) {
            array_push($suggestions, $row['title']);
        }
        if (strpos($row['author'], $search) !== false) {
            array_push($suggestions, $row['author']);
        }
        if (strpos($row['advisor'], $search) !== false) {
            array_push($suggestions, $row['advisor']);
        }
        // if (strpos($row['abstract'], $search) !== false) {
        //     array_push($suggestions, $row['abstract']);
        // }
        if (strpos($row['degree'], $search) !== false) {
            array_push($suggestions, $row['degree']);
        }
        if (strpos($row['URI'], $search) !== false) {
            array_push($suggestions, $row['URI']);
        }
        if (strpos($row['discipline'], $search) !== false) {
            array_push($suggestions, $row['discipline']);
        }
        if (strpos($row['language'], $search) !== false) {
            array_push($suggestions, $row['language']);
        }
    }

    print json_encode($suggestions);
}

<?php

require '../../vendor/autoload.php';



function normal_search($search)
{
    require '../mysql_login.php';

    $query = "SELECT * FROM pates_etds.etds WHERE title LIKE '%$search%' 
    OR author LIKE '%author%' OR advisor LIKE '%$search%'
    OR abstract LIKE '%$search%' OR university LIKE '%$search%' 
    OR degree LIKE '%$search%' OR '%$search%' 
    OR discipline LIKE '%$search%' OR language LIKE '%$search%';";

    return $connection->query($query);
}

<?php

include '../../constants.php';

// Try connecting into the Shields Search database.
$connection = new mysqli(SQL_SERVER, SQL_USERNAME, SQL_PASSWORD, DBNAME, 3306);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

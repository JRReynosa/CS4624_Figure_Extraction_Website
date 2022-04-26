<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../constants.php';

if (isset($_POST['password'])) {
    $entered_password = $_POST['password'];
    if ($entered_password == ADMIN_PASSWORD) {

        session_start();

        $_SESSION['admin_access'] = ADMIN;

        header("Location: ../public/views/index.php");
    }
}
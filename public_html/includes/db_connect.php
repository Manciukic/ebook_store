<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'EbookStore');
    if ($mysqli->connect_errno) {
        echo "We are experiencing problems. Try again later!";
        exit;
    }
?>
<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $mysqli = new mysqli('127.0.0.1', 'ebook_store', 'k1l3gg3mu0r3', 'EbookStore');
    if ($mysqli->connect_errno) {
        echo "We are experiencing problems. Try again later!";
        exit;
    }
?>
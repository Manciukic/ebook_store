<?php
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'EbookStore');
    if ($mysqli->connect_errno) {
        echo "We are experiencing problems. Try again later!";
        exit;
    }
?>
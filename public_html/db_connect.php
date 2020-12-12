<?php
    $mysqli = new mysqli('127.0.0.1', 'ebook_store', 'k1l3gg3mu0r3', 'EbookStore');
    if ($mysqli->connect_errno) {
        echo "We are experiencing problems. Try again later!";
        exit;
    }
?>
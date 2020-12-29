<?php
session_start();
require_once "includes/functions.php";
require_once "includes/error.php";


if (!isset($_GET["id"])){
    // bad request

    error_page(400, "No book ID provided");
}

if (!isset($_SESSION["user_id"])){
    // unauthorized
    error_page(403, "Nope");
}

$file = path_to_ebook_auth($_SESSION["user_id"], $_GET["id"]);
if (!$file){
    // unauthorized
    error_page(403, "Nope");
}

$filename = basename($file);

if (file_exists($file)) {
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Content-Type: '.mime_content_type($file));
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
} else {
    error_page(500, "It was not possible to retrieve your ebook. Try again later.");
}
?>
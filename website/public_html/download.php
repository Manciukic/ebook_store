<?php
session_start();
require_once "includes/functions.php";
require_once "includes/auth_functions.php";


if (!isset($_GET["id"])){
    // bad request

    $error_code=400;
    $error_msg="No book ID provided";
    include "includes/error.php";
    exit;
}

if (!isset($_SESSION["user_id"])){
    // unauthorized
    $error_code=403;
    $error_msg="Nope";

    include "includes/error.php";
    exit;
}

$file = path_to_ebook_auth($_SESSION["user_id"], $_GET["id"]);
if (!$file){
    // unauthorized
    $error_code=403;
    $error_msg="Nope";

    include "includes/error.php";
    exit;
}

$filename = basename($file);

if (file_exists($file)) {
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Content-Type: '.mime_content_type($file));
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
} else {
    $error_code = 500;
    $error_msg = "It was not possible to retrieve your ebook. Try again later.";
    include "includes/error.php";
    exit;
}
?>
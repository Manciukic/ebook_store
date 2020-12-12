<?php
session_start();
include "includes/functions.php";
include "includes/auth_functions.php";


if (!isset($_GET["id"])){
    // bad request

    include "includes/error.php";
    return;
}

if (!isset($_SESSION["user_id"])){
    // unauthorized

    include "includes/error.php";
    return;
}

$path = path_to_ebook_auth($_SESSION["user_id"], $_GET["id"]);
if (!$path){
    // unauthorized

    include "includes/error.php";
    return;
}

$abs_path = realpath($path);
$filename = basename($path);

header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Content-Type: '.mime_content_type($filename));
header('X-Sendfile: ' . $abs_path);

// Apache will take care of delivering the file
?>
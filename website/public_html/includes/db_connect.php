<?php
    require_once "includes/settings.php";
    require_once "includes/error.php";

    // raise exceptions in case of errors
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($mysqli->connect_errno) {
        fatal_error_page();
        exit;
    }
?>
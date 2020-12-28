<?php

require_once "includes/auth_functions.php";
require_once "includes/sessionUtil.php";
require_once "includes/functions.php";
require_once "includes/db_connect.php";

if (!isset($_GET['link'])) {
    $error_code = 400;
    $error_msg = "Malformed activation link. Make sure to copy the full link.";
    include "includes/error.php";
    exit;
}

$user = check_activation_link($_GET['link']);

if (!$user) {
    $error_code = 404;
    $error_msg = "The activation link you clicked was either expired or not existing. Please generate a new link by logging in.";
    include "includes/error.php";
    exit;
}

$query = $mysqli->prepare("UPDATE users SET activated=1 WHERE id=?");
$query->bind_param("i", $user["id"]);
$result = $query->execute();
if (!$result) {
    $error_code = 500;
    $error_msg = "There was an error activating your account. Please try again later with another activation link.";
    include "includes/error.php";
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title> E-book shop </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="generic-page">
        <h1>Your account is now activated. Log in to buy some Ebooks</h1>
    <main>
</body>

</html>
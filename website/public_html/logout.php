<?php
require_once "includes/functions.php";
session_start();
auth_log($_SESSION['user_email'], 'logout', true);
session_destroy();
//exit;
?>



<!DOCTYPE html>
<html>

<head>
    <title>
        Logout
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
<?php include "includes/header.php" ?>
<main class="logout-page">
    <h1>You have been disconnected.</h1>

</main>
</body>

</html>
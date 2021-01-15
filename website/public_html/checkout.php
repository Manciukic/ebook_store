<?php
session_start();
require_once "includes/functions.php";

if (isset($_GET['start'])){
    unset($_SESSION['stage']);
    if(!isset($_COOKIE['items'])){
        error_page(400, "There are no items in the cart");
    }
    $_SESSION['items'] = explode(',', $_COOKIE['items']);
}

if (!isset($_SESSION['stage']) || $_SESSION['stage'] == 0){
    // first stage of checkout: login

    include "includes/checkout_0_login.php";
} elseif ($_SESSION['stage'] == 1){
    // second stage of checkout: payment

    include "includes/checkout_1_payment.php";
} elseif ($_SESSION['stage'] == 2){
    // second stage of checkout: confirmation

    include "includes/checkout_2_confirmation.php";
} else {
    // wtf? Send user back to first stage
    error_log("checkout: undefined stage ".$_SESSION['stage']." resetting to first stage.");

    unset($_SESSION['stage']);

    include "includes/checkout_0_login.php";
}
?>


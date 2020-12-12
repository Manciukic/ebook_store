<?php
session_start();
include "includes/db_connect.php";

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
    include "includes/error.php";
}
?>


<?php
require_once "includes/functions.php";

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


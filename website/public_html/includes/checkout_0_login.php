<?php

// check if user has items in cart
if (empty($_SESSION['items'])) {
    // empty cart: how the hell did he get here?

    unset($_SESSION["stage"]);
    $error_code=400;
    $error_msg="Empty cart";
    include "includes/error.php";
    exit;
}

if (isset($_SESSION['user_id'])) {
    // User is already logged in: skip to stage 1
    include "includes/checkout_1_payment.php";
    exit;
}

$_SESSION['stage'] = 0;
// redirect user to login
header("location: login_form.php?redirect=checkout.php");
?>
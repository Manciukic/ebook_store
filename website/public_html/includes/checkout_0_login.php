<?php
require_once "includes/db_connect.php";
require_once "includes/error.php";

// check if user has items in cart
if (empty($_SESSION['items'])) {
    // empty cart: how the hell did he get here?

    unset($_SESSION["stage"]);
    error_page(400, "Empty cart");
}

if (isset($_SESSION['user_id'])) {
    // User is already logged in

    // check he owns no cart items 
    list($n, $new_cart) = remove_owned_ebooks($_SESSION['user_id'], $_SESSION["items"]);
    if ($n > 0){
        $_SESSION["items"] = $new_cart;

        // some ebooks were already owned -> back to cart
        header("location: cart.php?notice=removed_items");
        exit;
    } 

    // otherwise continue to stage 1
    include "includes/checkout_1_payment.php";
    exit;
}

$_SESSION['stage'] = 0;
// redirect user to login
header("location: login_form.php?redirect=checkout.php");
?>
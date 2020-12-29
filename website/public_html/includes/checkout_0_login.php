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
    global $mysqli;
    $cart = $_SESSION["items"];
    $nbooks =  count($cart);
    $book_ids_query = implode(',', array_fill(0, $nbooks, '?'));

    $stmt = $mysqli->prepare("
        SELECT OE.ebook_id as id
        FROM order_ebook OE 
            INNER JOIN orders O ON OE.order_id = O.id
        WHERE OE.ebook_id IN (" . $book_ids_query . ")
    ");
    $stmt->bind_param(str_repeat('i', $nbooks), ...$cart);
    $stmt->execute();
    $results = $stmt->get_result();
    if ($results->num_rows > 0){
        foreach ($results as $book){
            unset($cart[array_search($book['id'], $cart)]);
        }

        $_SESSION["items"] = $cart;

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
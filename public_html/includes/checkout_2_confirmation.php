<?php

// check if user has items in cart
if (empty($_SESSION['items'])){
    // empty cart: how the hell did he get here?
    $error_code=400;
    $error_msg="Empty cart";
    include "includes/error.php";
    return;
}

if (!isset($_SESSION['user_id'])) {
    // not logged in, wtf ?

    $error_code=403;
    $error_msg="You need to be logged in to see this page";
    include "includes/error.php";
    return;
}

if (!isset($_SESSION['card_id'])) {
    // no card inserted, wtf ?

    $error_code=400;
    $error_msg="No credit card selected";
    include "includes/error.php";
    return;
}

$_SESSION['stage'] = 2;

// TODO check inputs
if (isset($_POST["action"])){
    if ($_POST["action"] == "Confirm"){
        // Useful variables for preparing variable length statements
        $book_ids = $_SESSION['items'];
        $nbooks =  count($book_ids);
        $book_ids_query = implode(',', array_fill(0, $nbooks, "?"));

        /* Start transaction */
        $mysqli->begin_transaction();

        try{
            $insert_order_query = $mysqli->prepare("
                    INSERT INTO orders (`user_id`, `credit_card_id`, `time`, `price`)
                    SELECT ?, ?, NOW(), SUM(E.price)
                    FROM ebooks E
                    WHERE id IN (" . $book_ids_query . ")
                ");
            $insert_order_query->bind_param(str_repeat('i', $nbooks+2), $_SESSION["user_id"], $_SESSION["card_id"], ...$book_ids);
            $insert_order_query->execute();

            $order_id = $mysqli->insert_id;

            $insert_order_items_query = $mysqli->prepare("
                    INSERT INTO order_ebook (`order_id`, `ebook_id`, `price`)
                    SELECT ?, E.id, E.price
                    FROM ebooks E
                    WHERE id IN (" . $book_ids_query . ")
                ");
            $insert_order_items_query->bind_param(str_repeat('i', $nbooks+1), $order_id, ...$book_ids);
            $insert_order_items_query->execute();

            $mysqli->commit();
        } catch (mysqli_sql_exception $exception) {
            $mysqli->rollback();
        
            throw $exception; // TODO ?
        }

        unset($_SESSION["stage"]);
        unset($_SESSION["items"]);
        unset($_SESSION["card_id"]);
        header("Location: order.php?id=$order_id");
        return;
    } else{
        $_SESSION['stage'] = 0;
        header("Location: cart.php");
        return;
    }
}

$card_result = get_credit_card($_SESSION['card_id']);

if ($card_result->num_rows === 0){
    // Card not found

    $error_code=400;
    $error_msg="Invalid credit card";
    include "includes/error.php";
    return;
}

$card = $card_result->fetch_array();

?>

<!DOCTYPE html>
<html>

<head>
    <title>
        Checkout - Confirmation
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    
    <!-- TODO riepilogo -->
    You're paying with: **** **** **** <?php echo $card["last_digits"] ?> (<?php echo $card["expiration"] ?>)<br>
    
    <form action="checkout.php" method="post">
    <input type="submit" name="action" value="Confirm">
    <input type="submit" name="action" value="Abort">
    </form>
</body>
</html>

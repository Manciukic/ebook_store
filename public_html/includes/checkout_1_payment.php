<?php

// check if user has items in cart
if (empty($_SESSION['items'])){
    // empty cart: how the hell did he get here?
    include "includes/error.php";
    return;
}

if (!isset($_SESSION['user_id'])) {
    // not logged in, wtf ?

    include "includes/error.php";
    return;
}

$_SESSION['stage'] = 1;


// TODO check inputs
if (isset($_POST["card_id"])){
    if ($_POST["card_id"] == ""){
        if (!(isset($_POST["card_number"]) && isset($_POST["card_expiration"]) && isset($_POST["card_cvv"]))){
            // Missing card information

            include "includes/error.php";
            return;
        }

        $insert_card_query = $mysqli->prepare(
            "INSERT INTO credit_cards (`user_id`, `number`, `expiration`, `cvv`) 
                VALUES (?,?,?,?)"
        );
        $insert_card_query->bind_param("isss", $_SESSION['user_id'], $_POST["card_number"], $_POST["card_expiration"], $_POST["card_cvv"]);
        $insert_card_query->execute();
        $_SESSION['card_id'] = $mysqli->insert_id;
    } else {
        $_SESSION['card_id'] = intval($_POST["card_id"]);
    }

    include "includes/checkout_2_confirmation.php";
    return;
}

$card_result = get_credit_cards($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html>

<head>
    <title>
        Checkout - Payment
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    
    <!-- TODO riepilogo -->

    Choose your payment method:
    <form action="checkout.php" method="post">
    <?php while ($card = $card_result->fetch_array()) { ?>
        <input type="radio" name="card_id" value="<?php echo $card["id"] ?>">**** **** **** <?php echo $card["last_digits"] ?> (<?php echo $card["expiration"] ?>)<br>
    <?php } ?>
    <input type="radio" name="card_id" value="">Other card:<br/>
    Number: <input type="text" name="card_number" /><br/>
    Expiration: <input type="text" name="card_expiration" /><br/>
    CVV: <input type="password" name="card_cvv" /><br/>
    <input type="submit" value="Pay" />
    </form>
</body>
</html>

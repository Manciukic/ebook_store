<?php

// check if user has items in cart
if (empty($_SESSION['items'])) {
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
if (isset($_POST["card_id"])) {
    if ($_POST["card_id"] == "") {
        if (!(isset($_POST["card_number"]) && isset($_POST["card_expiration"]) && isset($_POST["card_cvv"]))) {
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


    <form action="checkout.php" method="post" class="stage-form">
        <label for="card_id">Choose your payment method:</label>
        <select name="card_id" onselect="" onchange="showAddCard(this)">
            <?php while ($card = $card_result->fetch_array()) { ?>
                <option value="<?php echo $card["id"] ?>">
                    **** **** **** <?php echo $card["last_digits"] ?> (<?php echo $card["expiration"] ?>)
                </option>
            <?php } ?>
            <option value="">
                Add a new card
            </option>
        </select>
        <div class="card-hidden" id="new-card-details">
            <label for="card_number">Number:</label>
            <input type="text" name="card_number" />
            <label for="card_expiration">Expiration:</label>
            <input type="text" name="card_expiration" />
            <label for="card_cvv">CVV:</label>
            <input type="password" name="card_cvv" />
        </div>
        <button type="submit">Pay</button>
    </form>
</body>

</html>
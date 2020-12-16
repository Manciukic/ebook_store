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

if (!isset($_SESSION['user_id'])) {
    // not logged in, wtf ?

    unset($_SESSION["stage"]);
    $error_code=403;
    $error_msg="You need to be logged in to see this page";
    include "includes/error.php";
    exit;
}

$_SESSION['stage'] = 1;
require_once "includes/validation_functions.php";

$errors = array();

if (isset($_POST["card_id"]) || isset($_POST["card_number"])){
    if (empty($_POST["card_id"])){
        if (!(isset($_POST["card_number"]) && isset($_POST["card_expiration"]) && isset($_POST["card_cvv"]))){
            $errors[] = "Plase fill all card information";
        } else {
            $card_number_valid = validate_card_number($_POST["card_number"]);
            if ($card_number_valid !== true)
                $errors[] = $card_number_valid;

            $card_expiration_valid = validate_card_expiration($_POST["card_expiration"]);
            if ($card_expiration_valid !== true)
                $errors[] = $card_expiration_valid;

            $card_cvv_valid = validate_card_cvv($_POST["card_cvv"]);
            if ($card_cvv_valid !== true)
                $errors[] = $card_cvv_valid;
        }

        if (empty($errors)){
            try{
                $insert_card_query = $mysqli->prepare(
                    "INSERT INTO credit_cards (`user_id`, `number`, `expiration`, `cvv`) 
                        VALUES (?,?,?,?)"
                );
                $insert_card_query->bind_param("isss", $_SESSION['user_id'], $_POST["card_number"], $_POST["card_expiration"], $_POST["card_cvv"]);
                $insert_card_query->execute();
                $_SESSION['card_id'] = $mysqli->insert_id;
            } catch (mysqli_sql_exception $exception) {
                $errors[] = "This card already exists";
            }
        }
    } else {
        $_SESSION['card_id'] = intval($_POST["card_id"]);
    }

    if (empty($errors)){
        include "includes/checkout_2_confirmation.php";
        exit;
    } // in case of error re-display page with errors
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

    <?php if (!empty($errors)) { ?>
        <div class="stage-error">
            <h3>Please fix these errors</h3>
            <ul>
                <?php foreach($errors as $error_msg){ ?>
                    <li> <?php echo $error_msg ?> </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
    
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
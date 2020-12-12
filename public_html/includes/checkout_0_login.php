<?php

// check if user has items in cart
if (empty($_SESSION['items'])) {
    // empty cart: how the hell did he get here?
    include "includes/error.php";
    return;
}

if (isset($_SESSION['user_id'])) {
    // User is already logged in: skip to stage 1
    include "includes/checkout_1_payment.php";
    return;
}

require_once "includes/auth_functions.php";

$_SESSION['stage'] = 0;

$error = null;

if (isset($_POST['user']) && isset($_POST['password'])) {
    $user_id = login($mysqli, $_POST['user'], $_POST['password']);
    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        include "includes/checkout_1_payment.php";
        return;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        Checkout - Login required
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>

    <main>
        <?php if ($error) { ?>
            <div class="stage-error-container">
                <p class="stage-error"><?php echo $error ?></p>
            </div>
        <?php } ?>

        <form action="checkout.php" method="post" class="stage-form">
            <label for="user">Username:</label>
            <input type="text" name="user">
            <label for="password">Password:</label>
            <input type="password" name="password">
            <button type="submit">Login</button>
        </form>
    </main>

    <!-- TODO Create new account -->
</body>

</html>
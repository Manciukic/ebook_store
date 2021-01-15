<?php

require_once "includes/sessionUtil.php";
require_once "includes/functions.php";
require_once "includes/validation_functions.php";
require_once "includes/db_connect.php";
require_once "includes/error.php";

if (isset($_POST['link'])){
    if (!validate_link($_POST['link'])){
        error_page(400, "Malformed activation link. Make sure to copy the full link.");
    }

    $user = check_activation_link($_POST['link']);

    if (!$user) {
        error_page(404,"The activation link you clicked was either expired or not existing. Please generate a new link by logging in.");
    }

    $query = $mysqli->prepare("UPDATE users SET activated=1 WHERE id=?");
    $query->bind_param("i", $user["id"]);
    $result = $query->execute();
    if (!$result) {
        error_page(500, "There was an error activating your account. Please try again later with another activation link.");
    }
    // ok
} else {
    if (!isset($_GET['link']) || !validate_link($_GET['link'])) {
        error_page(400, "Malformed activation link. Make sure to copy the full link.");
    }
    // show form
}

?>

<!DOCTYPE html>
<html>

<head>
    <title> E-book shop </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <?php if (isset($_POST['link'])){ ?>
        <main class="generic-page">
            <h1>Your account is now activated. Log in to buy some Ebooks</h1>
        <main>
    <?php } else { ?>
        <main class="form-page">
        <form action="activate.php" method="POST" name="form_pwchange" method="post" class="stage-form">
            <h1>
                Account activation
            </h1>
            <p>
                Activate your account by clicking the button below.
            </p>
            <input type="hidden" name="link" value="<?php echo htmlspecialchars()$_GET['link']) ?>" />
            <button type="submit" value="Click here to activate" class="btn-form">Click here to activate</button>
        </form>
    </main>
    <?php } ?>
</body>

</html>
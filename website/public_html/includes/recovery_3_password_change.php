<?php
// This is a new password set through the recovery link
$user = check_recovery_link($_POST['link']);
if (!$user) {
    $error_code = 404;
    $error_msg = "The recovery link you clicked was either expired or not existing. Please generate a new link.";
    include "includes/error.php";
    exit;
}

$password = $_POST['new_password'];
if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,127}$/", $password)) {
    $error_code = 400;
    $error_msg = "Password is not valid. A number, a lowercase and an uppercase char are needed. Password length can be 6 to 127";
    include "includes/error.php";
    exit;
}

$password = password_hash($password, PASSWORD_BCRYPT);    //Password hashing using BCRYPT
$mysqli->begin_transaction();
try {
    $query = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
    $query->bind_param("si", $password, $user["id"]);
    $result = $query->execute();
    if (!$result) {
        throw new mysqli_sql_exception();
    }

    $query = $mysqli->prepare("DELETE FROM recovery_links WHERE user_id = ? ");
    $query->bind_param("i", $user["id"]);
    $result = $query->execute();
    if (!$result) {
        throw new mysqli_sql_exception();
    }
} catch (mysqli_sql_exception $exception) {
    $result = false;
}

if (!$result) {
    $mysqli->rollback();
    $error_code = 500;
    $error_msg = "There was an error recovering your account. Please try again later with another recovery link.";
    include "includes/error.php";
    exit;
} else {
    $mysqli->commit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        Recover account
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="form-page">
        <div class="stage-form">
            <h1>Recover account</h1>
            <p>Success! Try logging in with the new password!</p>
        </div>
    </main>
    <script src="js/event_handler_validation.js"></script>
</body>

</html>
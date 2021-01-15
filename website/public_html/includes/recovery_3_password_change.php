<?php
require_once "includes/error.php";
require_once "includes/validation_functions.php";


$user = check_recovery_link($_POST['link']);
if (!$user) {
    error_page(404, "The recovery link you clicked was either expired or not existing. Please generate a new link.");
}


// Check the security answer
$correct_answer = get_secret_answer($user['id']);
if(!$correct_answer || $correct_answer['answer'] != $_POST['answer']){
    error_page(400, "The secret answer you provided is different from the one you used to register on this site.");
}

// This is a new password set through the recovery link
$password = $_POST['new_password'];
if (!validate_password($password)) {
    error_page(400, "Password is not valid. A number, a lowercase and an uppercase char are needed. Password length can be 6 to 127");
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
    error_page(500, "There was an error recovering your account. Please try again later with another recovery link.");
} else {
    $mysqli->commit();
    auth_log($user['email'], 'recover_change', true);
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
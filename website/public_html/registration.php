<?php

require_once "includes/validation_functions.php";
require_once "includes/functions.php";
require_once "includes/sessionUtil.php";
require_once "includes/error.php";

if (
    !isset($_POST['name']) ||
    !isset($_POST['password']) ||
    !isset($_POST['email']) ||
    !isset($_POST['answer']) ||
    !isset($_POST['customedQuestion']) ||
    !isset($_POST['secretQuestion']) ||
    !isset($_POST['g-recaptcha-response'])
) {

    error_page(400, "Provide all parameters.");
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$answer = $_POST['answer'];
$customedQuestion = $_POST['customedQuestion'];
$secretQuestion = $_POST['secretQuestion'];



// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_page(400, "Email is not valid");
}

// Password security validation
if (!validate_password($password)) {
    error_page(400, "Password is not valid. A number, a lowercase and an uppercase char are needed. Password length can be 6 to 127");
}

if (!validate_name($name)) {
    error_page(400, "Valid names may only contain characters and spaces");
}


// Secret question validation
if (
    $secretQuestion != "new" && !get_question($secretQuestion)
    || $secretQuestion == "new" && $customedQuestion == ""
) {
    error_page(400, "No secret question provided.");
}

if ($secretQuestion == "new") {
    $questionIndex = -1;
} else {
    $questionIndex = $secretQuestion;
}

/* Start transaction */
$mysqli->begin_transaction();

try {
    $password = password_hash($password, PASSWORD_BCRYPT);    //Password hashing using BCRYPT
    $queryText = $mysqli->prepare(      //Insert credentials in users table
        "INSERT INTO users(password,email,full_name) VALUES(?,?,?)"
    );
    $queryText->bind_param("sss", $password, $email, $name);
    if (!$result = $queryText->execute()) {
        error_log("Insert user failed: (" . $result->errno . ") " . $result->error);
        $mysqli->rollback();
        error_page(500, "There was an error creating this user. Please try again later.");
    }

    $queryText = $mysqli->prepare(      //To retrieve the user's id
        "select * from users where email=?"
    );
    $queryText->bind_param("s", $email);

    $queryText->execute();
    $result = $queryText->get_result();
    $userRow = $result->fetch_assoc();

    if (!$userRow) {
        $mysqli->rollback();
        error_page(500, "There was an error creating this user. Please try again later.");
    }
    $userId = $userRow['id'];

    $answer = password_hash($answer, PASSWORD_BCRYPT);
    if ($questionIndex == -1) {            //Insert answer to customed question into secret_answers
        $queryText = $mysqli->prepare(
            "INSERT INTO secret_answers(answer,custom_question,user_id) VALUES(?,?,?)"
        );
        $queryText->bind_param("sss", $answer, $customedQuestion, $userId);
        if (!$result = $queryText->execute()) {
            error_log("Insert custom answer failed: (" . $result->errno . ") " . $result->error);
            $mysqli->rollback();
            error_page(500, "There was an error creating this user. Please try again later.");
        }
    } else {       //Insert answer to default question into secret_answers
        $queryText = $mysqli->prepare(
            "INSERT INTO secret_answers(answer,question_id,user_id) VALUES(?,?,?)"
        );
        $queryText->bind_param("sss", $answer, $questionIndex, $userId);
        if (!$result = $queryText->execute()) {
            error_log("Insert secret answer failed: (" . $result->errno . ") " . $result->error);
            $mysqli->rollback();
            error_page(500, "There was an error creating this user. Please try again later.");
        }
    }

    $activation_link = create_activation_link($userId);

    if (!$activation_link) {
        error_log("Error creating activation link");
        $mysqli->rollback();
        error_page(500, "There was an error creating this user. Please try again later.");
    }

    $mysqli->commit();

    send_activation_link($userId, $activation_link);
    auth_log($email, 'register', true);
} catch (mysqli_sql_exception $exception) {
    $mysqli->rollback();

    if ($exception->getCode() == 1062) { // duplicate entry
        // notify real user
        $real_user = get_user_by_email($email);
        $msg = "Dear " . $real_user["full_name"] . ",\n" .
            "there was an attempt to register your email address in the " .
            "ebook store. \n" .
            "If it was you, we would like to inform you that you already " .
            "own an account and you can recover your password from a " .
            "link on the login form.\n" .
            "If it wasn't you, then someone is trying to hack into your " .
            "account. You should not click any suspect links or send any " .
            "of the links received from the Ebook Store to another " .
            "person.\n" .
            "Thank you for using the Ebook Store,\n" .
            "one of our automated penguins";
        sendmail($email, "Ebook Store: Security alert", $msg);
        auth_log($email, 'register', false);
        // continue as if nothing happened
    } else {
        error_log("SQL Error creating user(" . $exception->getCode() . "): " . $exception->getMessage());
        error_page(500, "There was an error creating this user. Please try again later.");
    }
}

// Captcha
$result = CheckCaptcha($_POST['g-recaptcha-response']);
if (!$result['success']) {
    error_page(400, "Captcha was not correctly solved");
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
    <main class="profile-page">
        <h1>Registration</h1>
        <h2>You registration has been recorded successfully. You should have received an email to activate your account.</h2>
    </main>
</body>

</html>
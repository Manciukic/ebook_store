<?php
require_once "includes/functions.php";
require_once "includes/error.php";
require_once "includes/validation_functions.php";

session_start();

$user_exists = false;
if (isset($_SESSION['user_id'])) {
    $user = get_user($_SESSION['user_id']);
    $user_exists = true;
}

if (
    $user_exists
    && isset($_POST['new_password'])
    && isset($_POST['old_password'])
) {

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // validate new password
    if (!validate_password($new_password)) {
        error_page(400, "Password is not valid. A number, a lowercase and an uppercase char are needed. Password length can be 6 to 127");
    }

    // check the old password is the actual password
    // Before hashing, we check password length (to prevent DoS from huge password hashing)
    if (
        strlen($old_password) < 6
        || strlen($old_password) > 127
        || !login($_SESSION['user_email'], $old_password)
    ) {
        error_page(401, "Wrong credentials");
    }

    $new_password = password_hash($new_password, PASSWORD_BCRYPT);    //Password hashing using BCRYPT
    $query = $mysqli->prepare("
            UPDATE users
            SET password = ?
            WHERE id = ?
        ");
    $query->bind_param("si", $new_password, $_SESSION['user_id']);
    if (!$query->execute()){
        error_page(500, "There was an error while changing the password. Please try again later.");
    }

    $msg = "Dear " . $user['full_name'] . ",\n" .
        "the password for you account on Ebook Store " .
        "has been changed successfully.\n" .
        "Thank you for using the Ebook Store,\n" .
        "one of our automated penguins";
    mail($_SESSION['user_email'], "Ebook Store: password changed", $msg, $mail_headers);

    $success_msg = "Your password has been changed successfully!";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        Password change
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="form-page">
        <?php if (!isset($user_exists) || !$user_exists) { ?>
            <h1>Not logged in</h1>
            <div class="profile-links">
                <a href="login_form.php">
                    Login
                </a>
            </div>
        <?php
        } else {
        ?>
            <?php if (isset($success_msg)) { ?>
                <div class="stage-error-container">
                    <p class="stage-error"><?php echo $success_msg ?></p>
                </div>
            <?php } ?>
            <form action="password_change.php" method="POST" name="form_pwchange" method="post" class="stage-form">
                <h1>
                    Password change
                </h1>
                <div class="form-field">
                    <label for="old_password">Old password</label>
                    <div class="password-field password-strength">
                        <input class="registrationInput" name="old_password" placeholder="Old password" type="password" />
                    </div>
                </div>

                <div class="form-field">
                    <label for="new_password">New password</label>
                    <div class="password-field password-strength">
                        <input class="registrationInput" name="new_password" id="new_password" placeholder="New password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,127}" oninput="validate(this); update_security(this, 'password-counter');" />
                        <p id="password-counter" class="field-error hidden"></p>
                    </div>
                    <p id="control_new_password" class="field-error hidden"></p>
                </div>
                <div class="form-field">
                    <input class="registrationInput" name="repassword" type="password" placeholder="Repeat new password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,127}" oninput="check_match(this, 'new_password');" />
                    <p id="control_repassword" class="field-error hidden"></p>
                </div>
                <button type="submit" value="Change password" class="btn-form">Change password</button>
            </form>
        <?php
        }
        ?>
    </main>
    <script src="js/event_handler_validation.js"></script>
</body>

</html>
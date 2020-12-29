<?php
require_once "includes/error.php";

$user = check_recovery_link($_GET['link']);
if (!$user) {
    error_page(404, "The recovery link you clicked was either expired or not existing. Please generate a new link.");
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
        <form action="recover.php" method="POST" name="form_pwchange" method="post" class="stage-form">
            <h1>
                Recover account
            </h1>
            <p>
                You can set your new password now.
            </p>
            <div class="form-field">
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
            <input type="hidden" name="link" value="<?php echo $_GET['link'] ?>" />
            <button type="submit" value="Set password" class="btn-form">Set password</button>
        </form>
    </main>
    <script src="js/event_handler_validation.js"></script>
</body>

</html>
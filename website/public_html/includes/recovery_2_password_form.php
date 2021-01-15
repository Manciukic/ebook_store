<?php
require_once "includes/error.php";

$user = check_recovery_link($_GET['link']);
if (!$user) {
    error_page(404, "The recovery link you clicked was either expired or not existing. Please generate a new link.");
}

$secret_question = get_question_for_user($user['id']);
if(!$secret_question){
    error_page(400, "We are having problems. Try again later.");
}
$secret_question = $secret_question['question'];
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
            <div class="form-field">
                <label for="question">Security question<label>
                <input class="registrationInput" disabled value="<?php echo $secret_question ?>" id="question"/>
            </div>
            <div class="form-field">
                <input class="registrationInput" placeholder="Answer" name="answer" id="answer" />
            </div>
            <div class="form-field">
                <div class="password-field password-strength">
                    <label for="new_password">Type your new password</label>
                    <input class="registrationInput" name="new_password" id="new_password" placeholder="New password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,127}" oninput="validate(this); update_security(this, 'password-counter', ['<?php echo $user['email'];?>', '<?php echo $user['full_name'];?>'] , []);" />
                    <p id="password-counter" class="field-error hidden"></p>
                </div>
                <p id="control_new_password" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <label for="new_password">Retype the password</label>
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
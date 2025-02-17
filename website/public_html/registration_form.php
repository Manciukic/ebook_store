<?php
$ERRORS = array(
	"name" => "Please, provide a valid name. Names may include only letters, spaces, '.' and '-'.",
    "password" => "Your password does not satisfy our safety requirements.",
    "answer" => "Please provide a valid secret answer.",
    "question" => "Please provide a valid secret question.",
    "email" => "Please provide a valid email.",
    "missing" => "Some fields are missing.",
    "captcha" => "Captcha validation failed. Retry.",
	"default" => "Please provide all correct fields"
);
require_once("includes/functions.php");
$questions = get_questions();
if (!$questions) {
    include "includes/error.php";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title> Registration Form </title>
    <?php include "includes/include.php" ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="form-page" type="registration">
        <form action="./registration.php" name="form_registration" method="post" class="stage-form" onsubmit="registrate()">
            <h1>
                Registration
            </h1>
            <?php
                if (isset($_GET['error'])) {
                    $error_msg = $ERRORS[$_GET['error']] ?? $ERRORS["default"];
            ?>
                    <div class="stage-error-container">
                        <p class="stage-error"><?php echo $error_msg ?></p>
                    </div>
            <?php } ?>
            <div class="form-field">
                <label for="email">Email</label>
                <?php
                // Note: browsers automatically check email type inputs to look like a proper email address. 
                // The pattern is used as an additional check and, in this case, it is needed to ensure we have a tld part too
                ?>
                <input type="email" id="email-field" class="registrationInput"
                name="email" placeholder="Email"
                pattern="^[a-zA-Z0-9\-_\.\+]+@[a-zA-Z0-9\-_\.\+]+(\.[a-zA-Z0-9\-_\.\+]+)+$" 
                oninput="validate(this);"/>
                <p id="control_email" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <label for="name">Name</label>
                <input class="registrationInput" id="name-field" name="name" placeholder="Full name" pattern='^[^\^<,"@\/\{\}\(\)\*$%\?=>:|;#0-9]+$' oninput="validate(this);" />
                <p id="control_name" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <label for="password">Password</label>
                <div class="password-field password-strength">
                    <input class="registrationInput" name="password" placeholder="Password" type="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,127}" oninput="validate(this); update_security(this, 'password-counter', [], ['name-field', 'email-field']);" />
                    <p id="password-counter" class="field-error hidden"></p>
                </div>
                <p id="control_password" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <input class="registrationInput" name="repassword" type="password" placeholder="Repassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,127}" oninput="check_match(this, 'password');"/>
                <p id="control_repassword" class="field-error hidden"></p>
            </div>

            <div class="form-field">
                <label for="secretQuestion">Secret question</label>
                <select name="secretQuestion" onchange="showCustomSecretQuestion(this)">
                    <option disabled selected>Select a secret question</option>
                    <option value="new">Write your own question</option>
                    <?php
                    while ($question = $questions->fetch_array()) { ?>
                        <option value="<?php echo $question['id'] ?>">
                            <?php echo $question['question'] ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="hidden" id="new-question">
                <input class="registrationInput" name="customedQuestion" placeholder="Write a question">
                <p id="control_customedQuestion" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <input class="registrationInput" name="answer" placeholder="Answer" />
                <p id="control_answer" class="field-error hidden"></p>
            </div>

            <div class="account_creation">
                <div class="form-group">
                    <div class="form-field">
                        <div class="g-recaptcha"  data-sitekey="<?= $RECAPTCHA_SITEKEY ?>"></div>
                    </div>
                </div>
            </div>
            <div class="form-field">
                <span class="control-log" id="regControl" contenteditable="true"></span>
            </div>

            <button type="submit" value="Sign Up" class="btn-form">Sign Up</button>
            <a href="./login_form.php"> Already registered? Click here!</a>
        </form>
    </main>
</body>


<?php

if(isset($_POST['submit']))
{
    // Call the function CheckCaptcha
    $result = CheckCaptcha($_POST['g-recaptcha-response']);

    if ($result['success']) {
        //If the user has checked the Captcha box
        echo "Captcha verified Successfully";

    } else {
        // If the CAPTCHA box wasn't checked
        echo '<script>alert("Error Message");</script>';
    }
}
?>

<script type="text/javascript" src="js/zxcvbn.js"></script>
<script src="./js/event_handler_registration.js"> </script>
<script src="./js/event_handler_validation.js"> </script>

</html>
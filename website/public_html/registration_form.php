<?php
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
            <div class="form-field">
                <label for="email">Email</label>
                <?php
                // Note: browsers automatically check email type inputs to look like a proper email address. 
                // The pattern is used as an additional check and, in this case, it is needed to ensure we have a tld part too
                ?>
                <input type="email" class="registrationInput"
                name="email" placeholder="Email"
                pattern="^[a-zA-Z0-9\-_\.\+]+@[a-zA-Z0-9\-_\.\+]+(\.[a-zA-Z0-9\-_\.\+]+)+$" 
                oninput="validate(this);"/>
                <p id="control_email" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <label for="password">Password</label>
                <div class="password-field password-strength">
                    <input class="registrationInput" name="password" placeholder="Password" type="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,127}" oninput="validate(this); update_security(this, 'password-counter');" />
                    <p id="password-counter" class="field-error hidden"></p>
                </div>
                <p id="control_password" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <input class="registrationInput" name="repassword" type="password" placeholder="Repassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,127}" oninput="check_match(this, 'password');"/>
                <p id="control_repassword" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <label for="name">Name</label>
                <input class="registrationInput" name="name" placeholder="Full name" pattern='^[^\^<,"@\/\{\}\(\)\*$%\?=>:|;#]+$' oninput="validate(this);" />
                <p id="control_name" class="field-error hidden"></p>
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


<script src="./js/event_handler_registration.js"> </script>
<script src="./js/event_handler_validation.js"> </script>

</html>
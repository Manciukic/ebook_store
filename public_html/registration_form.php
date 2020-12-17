<!DOCTYPE html>
<html>

<head>
    <title> Registration Form </title>
    <?php include "includes/functions.php" ?>
    <?php include "includes/include.php" ?>

</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="form-page" type="registration">
        <form action="./registration.php" name="form_registration" method="post" class="stage-form">
            <h1>
                Registration
            </h1>
            <div class="form-field">
                <label for="username">Username</label>
                <input class="registrationInput" name="username" placeholder="Username" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,15}" />
                <p id="control_username" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <label for="password">Password</label>
                <div class="password-field password-strength">
                    <input class="registrationInput" name="password" placeholder="Password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,15}" />
                    <p id="password-counter" class="field-error hidden"></p>
                </div>
                <p id="control_password" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <input class="registrationInput" name="repassword" type="password" placeholder="Repassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,15}" />
                <p id="control_repassword" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <label for="name">Name</label>
                <input class="registrationInput" name="name" placeholder="Full name" />
                <p id="control_name" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <label for="email">Email</label>
                <input class="registrationInput" name="email" placeholder="Email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" />
                <p id="control_email" class="field-error hidden"></p>
            </div>
            <div class="form-field">
                <label for="defaultQuestion">Secret question</label>
                <select name="defaultQuestion" onchange="showCustomSecretQuestion(this)">
                    <option disabled selected>Select a secret question</option>
                    <option value="new">Write your own question</option>
                    <option>What's your mother's last name?</option>
                    <option>In what city did your parents meet?</option>
                    <option>What was the name of your primary school?</option>
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

            <div class="form-field">
                <span class="control-log" id="regControl" contenteditable="true"></span>
            </div>

            <button type="submit" value="Sign Up" class="btn-form">Sign Up</button>
            <a href="./login_form.php"> Already registered? Click here!</a>
        </form>
    </main>
</body>

<script src="./js/event_handler_registration.js"> </script>

</html>
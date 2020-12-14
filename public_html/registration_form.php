

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
	<br>
	<h1>
            Registration 
        </h1>
		<form action="./registration.php" name="form_registration" method="post">

                <input class="control" id="control_username" value=""  readonly="readonly" >
				<input class="registrationInput" name="username" placeholder="Username" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,15}"/>
                <br>
                <input class="control" id="control_password" value=""  readonly="readonly">
                <input class="registrationInput" name="password" placeholder="Password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,15}"/>
                <input class="control" name="passwordCounter" value=""  readonly="readonly" size=10>
                <br>
                <input class="control" id="control_repassword" value=""  readonly="readonly">
                <input class="registrationInput" name="repassword" type="password" placeholder="Repassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,15}"/>
                <br>
                <input class="control" id="control_name" value=""  readonly="readonly">
                <input class="registrationInput" name="name" placeholder="Full name"/>
                <br>
                <input class="control" id="control_email" value=""  readonly="readonly">
                <input class="registrationInput" name="email" placeholder="Email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+"/>
                <br>
                <div class="form-input">
                     <select name="defaultQuestion">
                        <option value="" disabled selected>Select a secret question</option>
                        <option>What's your mother's last name?</option>
                        <option>In what city did your parents meet?</option>
                        <option>What was the name of your primary school?</option>
                    </select>
                </div>

                <div class="form-input">
                    <span>or</span>
                </div>

                <input class="control" id="control_customedQuestion" value=""  readonly="readonly">
                <input class="registrationInput" name="customedQuestion" placeholder="Write a question">
                <br>
                <input class="control" id="control_answer" value=""  readonly="readonly">
                <input class="registrationInput" name="answer" placeholder="Answer"/>
                <br>

                <div class="form-input">
                    <span class="control-log" id="regControl" contenteditable="true"></span>
                </div>
                <br>
                <br>
                <div class="form-input">
                    <input type="submit" value="Sign Up" class="btn-form"/>
                </div>
                <br>
                <div class="form-input">
                    <a href="./login_form.php" > Already registered? Click here!</a>
                </div>
		</form>
    </main>
</body>

<script src="./js/event_handler_registration.js"> </script>

</html>

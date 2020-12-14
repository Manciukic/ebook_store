
<!DOCTYPE html>
<html>

<head>
	<title> Login Form </title>
    <?php include "includes/functions.php" ?>
    <?php include "includes/include.php" ?>


</head>
<body>

    <?php include "includes/header.php" ?>
    <main class="form-page" type="login">
	<br>
	<h1>
            Login 
        </h1>
		<form action="./authentication.php" name="form_login" method="post">
			<div class="form-input">
				<input class="loginInput" name="username" placeholder="Username"/>
			</div>
			<div class="form-input">
				<input class="loginInput" name="password" type="password" placeholder="Password"/>
			</div>
            <div class="form-input">

                <span class="control-log" id="emtpyFieldsLog">Fill all the fields!</span>
            </div>
            <br>
			<br>
			<?php
				if (isset($_GET['errorMessage'])){
					echo '<div style="color:red";>';
					echo '<span>' . $_GET['errorMessage'] . '</span>';
                    echo '</div>';
                    echo '<br><br>';
				}
			?>  
			<div class="form-input">
				<input  type="submit" value="Sign in" class="btn-form"/>
			</div>
			<br>
            <div class="form-input">
                <a href="./registration_form.php" > Not registered yet? Click here!</a>
            </div>

		</form>
    </main>
</body>

<script src="js/event_handler_login.js"> </script>

</html>

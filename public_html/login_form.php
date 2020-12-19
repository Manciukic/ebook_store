<?php
$ERRORS = array(
	"invalid" => "Your credentials are invalid.",
	"default" => "There was an error during login. Please try later."
)
?>
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

		<?php
		if (isset($_GET['error'])) {
			$error_msg = $ERRORS[$_GET['error']] ?? $ERRORS["default"];
		?>
			<div class="stage-error-container">
				<p class="stage-error"><?php echo $error_msg ?></p>
			</div>
		<?php } ?>
		<form action="./authentication.php" name="form_login" method="post" class="stage-form">
			<h1>
				Login
			</h1>
			<input class="loginInput" name="email" placeholder="Email" />
			<input class="loginInput" name="password" type="password" placeholder="Password" />
			<span class="control-log" id="emtpyFieldsLog">Fill all the fields!</span>
			<button type="submit" value="Sign in" class="btn-form">Sign in</button>
			<a href="./registration_form.php"> Not registered yet? Click here!</a>
		</form>
	</main>
</body>

<script src="js/event_handler_login.js"> </script>

</html>
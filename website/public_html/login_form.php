<?php
$ERRORS = array(
	"invalid" => "Your credentials are invalid. Be careful: your account will be locked for 5 minutes after 5 consecutive failed login attempts.",
	"inactive" => "Your account is not activated. We sent you a new activation link.",
	"disabled" => "Your account has been temporarly disabled. Try later.",
	"default" => "There was an error during login. Please try later."
);
$submit_url = isset($_GET['redirect']) ? "authentication.php?redirect=".$_GET['redirect'] : "authentication.php";
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
		<form action="<?= $submit_url ?>" name="form_login" method="post" class="stage-form">
			<h1>
				Login
			</h1>
			<input class="loginInput" name="email" placeholder="Email" />
			<input class="loginInput" name="password" type="password" placeholder="Password" />
			<span class="control-log" id="emtpyFieldsLog">Fill all the fields!</span>
			<div><a href="./recover.php"> Have you lost your password?</a></div>
			<button type="submit" value="Sign in" class="btn-form">Sign in</button>
			<a href="./registration_form.php"> Not registered yet? Click here!</a>
		</form>
	</main>
</body>

<script src="js/event_handler_login.js"> </script>

</html>
<?php
require_once "includes/functions.php";
require_once "includes/auth_functions.php";

session_start();

$logged_in = false;
if (isset($_SESSION['user_id'])) {
    $user = get_user($_SESSION['user_id']);
    $logged_in = true;
}

if (!$logged_in && isset($_POST['email'])) {
    // This is a request for a recovery link to be sent
    // If it fails, we behave like nothing happened to prevent user enumeration
    if($user_email = get_user_by_email($_POST['email'])){
        send_recovery_link($user_email['id']);
    }
}

if (!$logged_in && isset($_GET['link'])){
    $user = check_recovery_link($_GET['link']);
    if (!$user){
        $error_code=404;
        $error_msg="The recovery link you clicked was either expired or not existing. Please generate a new link.";
        include "includes/error.php";
        exit;
    }
}

if (!$logged_in && isset($_POST['new_password']) && isset($_POST['link'])) {
    // This is a new password set through the recovery link
    $user = check_recovery_link($_POST['link']);
    if (!$user){
        $error_code=404;
        $error_msg="The recovery link you clicked was either expired or not existing. Please generate a new link.";
        include "includes/error.php";
        exit;
    }

    $mysqli->begin_transaction();
    try {
        $query = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
        $query->bind_param("si", $_POST['new_password'], $user["id"]);
        $result=$query->execute();
        if(!$result){
            throw new mysqli_sql_exception();
        }
        
        $query = $mysqli->prepare("DELETE FROM recovery_links WHERE user_id = ? ");
        $query->bind_param("i", $user["id"]);
        $result=$query->execute();
        if(!$result){
            throw new mysqli_sql_exception();
        }
    } catch (mysqli_sql_exception $exception) {
        $result = false;
    }

    if (!$result){
        $mysqli->rollback();
        $error_code=500;
        $error_msg="There was an error recovering your account. Please try again later with another recovery link.";
        include "includes/error.php";
        exit;
    } else {
        $mysqli->commit();
    }
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
    <?php if ($logged_in) { ?>
        <main class="profile-page">
            <h1>You are logged in</h1>
            <div class="profile-links">
                You can't recover your account if you are currently using it :-)
            </div>
        <?php
    } else {
        ?>
            <main class="form-page">
                <?php if (isset($success_msg)) { ?>
                    <div class="stage-error-container">
                        <p class="stage-error"><?php echo $success_msg ?></p>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['link'])) { ?>
                    <form action="recover.php" method="POST" name="form_pwchange" method="post" class="stage-form">
                        <h1>
                            Recover account
                        </h1>
                        <p>
                            You can set your new password now.
                        </p>
                        <div class="form-field">
                            <div class="password-field password-strength">
                                <input class="registrationInput" name="new_password" id="new_password" placeholder="New password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,127}" oninput="validate(this);" />
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
                <?php } else if (isset($_POST['new_password']) && isset($_POST['link'])) { ?>
                    <div class="stage-form">
                        <h1>Recover account</h1>
                        <p>Success! Try logging in with the new password!</p>
                    </div>
                <?php } else { ?>
                    <form action="recover.php" method="POST" name="form_pwchange" method="post" class="stage-form">
                        <h1>
                            Recover account
                        </h1>
                        <?php if (isset($_POST['email'])) { ?>
                            <p>Recovery link successfully sent</p>
                        <?php } else { ?>
                            <input class="loginInput" name="email" placeholder="Email" />
                            <button type="submit" value="Set password" class="btn-form">Request recovery link</button>
                        <?php } ?>
                    </form>
                <?php } ?>
                </form>
            <?php
        }
            ?>
            </main>
            <script src="js/event_handler_pwchange.js"></script>
</body>

</html>
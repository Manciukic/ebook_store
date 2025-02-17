<?php
// This is a request for a recovery link to be sent
// If it fails, we behave like nothing happened to prevent user enumeration
if ($user_email = get_user_by_email($_POST['email'])) {
    if ($user_email['activated']){
        send_recovery_link($user_email['id']);
        auth_log($_POST['email'], 'recover_send', true);
    } else {
        send_activation_link($user_email['id']);
        auth_log($_POST['email'], 'recover_send', false);
    }
} else {
    auth_log($_POST['email'], 'recover_send', false);
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
            <p>Recovery link successfully sent</p>
        </form>
    </main>
    <script src="js/event_handler_validation.js"></script>
</body>

</html>
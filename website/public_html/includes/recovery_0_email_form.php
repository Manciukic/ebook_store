<!DOCTYPE html>
<html>

<head>
    <title>
        Recover account
    </title>
    <?php include "includes/include.php" ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="form-page">
        <form action="recover.php" method="POST" name="form_pwchange" method="post" class="stage-form">
            <h1>
                Recover account
            </h1>
            <input class="loginInput" name="email" placeholder="Email" />
            <div class="form-field">
                <div class="g-recaptcha"  data-sitekey="<?= $RECAPTCHA_SITEKEY ?>"></div>
            </div>
            <button type="submit" value="Set password" class="btn-form">Request recovery link</button>
        </form>
    </main>
    <script src="js/event_handler_validation.js"></script>
</body>

</html>
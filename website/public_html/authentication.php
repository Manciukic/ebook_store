<?php
    require_once "includes/sessionUtil.php";
    require_once "includes/db_connect.php";
    require_once "includes/error.php";
    require_once "includes/functions.php";

    if (!isset($_POST['email']) || !isset($_POST['password'])){
        error_page(400, "No email or password provided.");
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $error_url = isset($_GET['redirect']) ? "login_form.php?redirect=".$_GET['redirect']."&error" : "login_form.php?error";
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : "index.php";

    session_start();
    $login_query = $mysqli->prepare(
        "SELECT *, UNIX_TIMESTAMP(U.disabled_until) AS disabled_until_tc
            FROM users U
            WHERE U.email = ?"
    );

    $login_query->bind_param("s", $email);
    if (!$login_query->execute()){
        error_page(500, "There was an error. Retry later");
    }
    $login_result = $login_query->get_result();
    if (!$login_result){
        error_page(500, "There was an error. Retry later");
    }
    $user = $login_result->fetch_array();

    if (!$user) {    
        // Email does not exists
        header("location: $error_url=invalid");
        auth_log($email, 'login', false);
        exit;
    } 

    $user['enabled'] = $user["disabled_until_tc"] ? ($user["disabled_until_tc"] < time()) : true;
    
    if (!password_verify($password,$user['password'])){  
        // Password is wrong
        
        // account locking (do not lock if already locked)
        if ($user['enabled']){
            if ($user['failed_login_attempts']+1 >= 5){
                // send alert and lock
                $lock_query = $mysqli->prepare(
                        "UPDATE users
                        SET disabled_until= DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 5 MINUTE),
                            failed_login_attempts = 0
                        WHERE email = ?"
                        );
                $lock_query->bind_param("s",  $email);
                $lock_query->execute();

                global $BASE_URL;

                $msg = "Dear ".$user["full_name"].",\n" .
                    "you are receiving this email because your " .
                    "account has been disabled for 5 minutes due to 5 ".
                    "consecutive failed login attempts.\n" .
                    "If you forgot your password, you can recover it " .
                    "at the following link: " .
                    $BASE_URL . "recover.php\n" .
                    "If it wasn't you, we suggest you change your " .
                    "password from within your profile page.\n" .
                    "Thank you for using the Ebook Store,\n" .
                    "one of our automated penguins";

                sendmail($email, "Ebook Store: Account Locked", $msg);
            } else {
                // increase counter
                $new_attempt_query = $mysqli->prepare(  //number of failed attempts is increased
                        "UPDATE users
                            SET
                            failed_login_attempts=failed_login_attempts+1              
                            WHERE email = ?"
                    );
                $new_attempt_query->bind_param("s", $email);
                $new_attempt_query->execute();
            }
        }

        auth_log($user['email'], 'login', false);

        // in any case show generic error
        header("location: $error_url=invalid");
        exit;
    } 

    if (!$user['enabled']){
        auth_log($user['email'], 'login', false);
        // user is not enabled
        header("location: $error_url=invalid");
        exit;
    } 
    
    if (!$user['activated']){
        auth_log($user['email'], 'login', false);
        // user is not activated
        send_activation_link($user["id"]);
        header("location: $error_url=inactive");
        exit;
    } 

    // OK
    setSession($user['email'], $user['id']);
    if ($user['failed_login_attempts'] >= 1){   // reset login attempts
        $reset_attempts_query = $mysqli->prepare(
            "UPDATE users
            SET failed_login_attempts=0
            WHERE email = ?"
            );
        $reset_attempts_query->bind_param("s",  $email);
        $reset_attempts_query->execute();
    }

    auth_log($user['email'], 'login', true);

    header("location: $redirect");
?>

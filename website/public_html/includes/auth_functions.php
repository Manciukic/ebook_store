<?php
    require_once "includes/sessionUtil.php";
    require_once "includes/db_connect.php";
    require_once "includes/functions.php";

    function login($email, $password) {
        global $mysqli, $mail_headers;
        //$password=password_hash($password, PASSWORD_BCRYPT);    //Password hashing using BCRYPT
        $login_query = $mysqli->prepare(
            "SELECT *, UNIX_TIMESTAMP(U.disabled_until) AS disabled_until_tc
                FROM users U
                WHERE U.email = ?"
        );

        $login_query->bind_param("s", $email);
        $login_query->execute();
        $login_result = $login_query->get_result();
        $user_row = $login_result->fetch_array();

        if ($user_row) {    //Check wether the email exists
            $user_row['enabled'] = $user_row["disabled_until_tc"] ? ($user_row["disabled_until_tc"] < time()) : true;
            error_log(strtotime($user_row["disabled_until"])." <? ".time());
            if (password_verify($password,$user_row['password'])){  //Check wether the password is correct
                if ($user_row['activated'] && $user_row['enabled']) {   //Set the session only if the user is activated and not disabled
                    setSession($user_row['email'], $user_row['id']);
                    if ($user_row['failed_login_attempts'] >= 1){
                        $reset_attempts_query = $mysqli->prepare(
                            "UPDATE users
                            SET failed_login_attempts=0
                            WHERE email = ?"
                            );
                        $reset_attempts_query->bind_param("s",  $email);
                        $reset_attempts_query->execute();
                    }
                }
                return $user_row;
            }
            else {
                if ($user_row['enabled']){
                    if ($user_row['failed_login_attempts']+1 >= 5){
                        $lock_query = $mysqli->prepare(
                                "UPDATE users
                                SET disabled_until= DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 5 MINUTE),
                                    failed_login_attempts = 0
                                WHERE email = ?"
                                );
                        $lock_query->bind_param("s",  $email);
                        $lock_query->execute();

                        global $BASE_URL;

                        $msg = "Dear ".$user_row["full_name"].",\n" .
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

                        mail($email, "Ebook Store: Account Locked", $msg, $mail_headers);
                    } else {
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
            }
        }
        return false;
    } 

    function path_to_ebook_auth($user_id, $ebook_id){
        global $mysqli;
        $ownership_query = $mysqli->prepare(
            "SELECT E.path AS `path`
                FROM order_ebook OE
                    INNER JOIN orders O ON (O.id=OE.order_id)
                    INNER JOIN ebooks E ON (E.id=OE.ebook_id)
                WHERE O.user_id = ? AND OE.ebook_id = ?"
        );
        // TODO hash password
        $ownership_query->bind_param("ii", $user_id, $ebook_id);
        $ownership_query->execute();
        $ownership_query_result = $ownership_query->get_result();
        $ebook_row = $ownership_query_result->fetch_row();
        if ($ebook_row){
            return $ebook_row[0];
        } else {
            return null;
        }
    }

?>
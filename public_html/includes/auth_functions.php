<?php
    require_once "includes/sessionUtil.php";
    require_once "includes/db_connect.php";
    require_once "includes/functions.php";

    function login($email, $password) {
        global $mysqli;
        //$password=password_hash($password, PASSWORD_BCRYPT);    //Password hashing using BCRYPT
        $login_query = $mysqli->prepare(
            "SELECT *
                FROM users U
                WHERE U.email = ?"
        );

        $login_query->bind_param("s", $email);
        $login_query->execute();
        $login_result = $login_query->get_result();
        $user_row = $login_result->fetch_array();

        if ($user_row) {    //Check wether the email exists
            if (password_verify($password,$user_row['password'])){  //Check wether the password is correct
                if ($user_row['activated'] && !$user_row['disabled_until']) {   //Set the session only if the user is activated and not disabled
                    setSession($user_row['email'], $user_row['id']);
                }
                return $user_row;
            }
            else {

                if ($user_row['failed_login_attempts']>=4){
                    $lock_query = $mysqli->prepare(
                               "UPDATE users
                            SET
                            disabled_until= DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 5 MINUTE)
                            WHERE email = ?;
                            #DROP EVENT IF EXISTS account_unlocker;
                            #CREATE EVENT account_unlocker
                            #ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 5 MINUTE
                            #DO UPDATE users u
                            #SET u.disabled_until = NULL
                            #AND
                            #u.failed_login_attempts=0
                            #WHERE u.email = ?;"
                            );
                    $lock_query->bind_param("s",  $email);
                    $lock_query->execute();
                }
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
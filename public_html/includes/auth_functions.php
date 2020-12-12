<?php
    function login($mysqli, $user, $password) {
        $login_query = $mysqli->prepare(
            "SELECT U.id
                FROM users U
                WHERE (U.username = ? OR U.email = ?) AND U.password = ?"
        );
        // TODO hash password   
        $login_query->bind_param("sss", $user, $user, $password);
        $login_query->execute();
        $login_result = $login_query->get_result();
        $user_row = $login_result->fetch_row();
        if ($user_row){
            return $user_row[0];
        } else {
            return null;
        }
    } 
?>
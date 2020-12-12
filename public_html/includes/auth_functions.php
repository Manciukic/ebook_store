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
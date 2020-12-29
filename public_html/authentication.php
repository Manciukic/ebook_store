<?php

    require_once "includes/auth_functions.php";
    require_once "includes/sessionUtil.php";
 
    session_start();
    $user = login($_POST['email'], $_POST['password']);


    if (!$user){
        header('location: ./login_form.php?error=invalid');
    }
    elseif(!$user['enabled']){
        header('location: ./login_form.php?error=invalid');
    }
    elseif (!$user["activated"]) {
        send_activation_link($user["id"]);
        header('location: ./login_form.php?error=inactive');
    }
    else {
        header('location: ./index.php');
    }
?>

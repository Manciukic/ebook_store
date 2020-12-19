<?php

    require_once "includes/auth_functions.php";
    require_once "includes/sessionUtil.php";
 
    session_start();
    $user = login($_POST['email'], $_POST['password']);

    if ($user){
        header('location: ./index.php');
    } else {
        header('location: ./login_form.php?error=invalid');
    }
?>

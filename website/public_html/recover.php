<?php
require_once "includes/functions.php";
require_once "includes/auth_functions.php";

session_start();

$logged_in = false;
if (isset($_SESSION['user_id'])) {
    $user = get_user($_SESSION['user_id']);
    $logged_in = true;
}

if($logged_in){
    include "includes/recovery_logged.php";
    exit;
} 

if(!isset($_POST['email']) && !isset($_GET['link']) && !isset($_POST['link'])){
    // Firs step: we have to display the form for recovery request
    include "includes/recovery_0_email_form.php";
} else if (isset($_POST['email'])){
    // This is where we handle the request for a recovery link to be sent
    // If it fails, we behave like nothing happened to prevent user enumeration
    include "includes/recovery_1_handle_req.php";
} else if (isset($_GET['link']) && !isset($_POST['new_password'])){
    // User clicked the recovery link, we handle it
    include "includes/recovery_2_password_form.php";
} else if (isset($_POST['link']) && isset($_POST['new_password'])){
    // User provided the new password
    include "includes/recovery_3_password_change.php";
}
<?php
require_once "includes/validation_functions.php";
require_once "includes/functions.php";
require_once "includes/error.php";

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

if (
    (isset($_GET['link']) && !validate_link($_GET['link'])) ||
    (isset($_POST['link']) && !validate_link($_POST['link']))
){
    error_page(400, "Malformed activation link. Make sure to copy the full link.");
}


if(!isset($_POST['email']) && !isset($_GET['link']) && !isset($_POST['link'])){
    // Firs step: we have to display the form for recovery request
    include "includes/recovery_0_email_form.php";
} else if (isset($_POST['email'])){
    // Captcha
    $result = CheckCaptcha($_POST['g-recaptcha-response']);
    if (!$result['success']) {
        error_page(400, "Captcha was not correctly solved");
    }

    // This is where we handle the request for a recovery link to be sent
    // If it fails, we behave like nothing happened to prevent user enumeration
    include "includes/recovery_1_handle_req.php";
} else if (isset($_GET['link']) && !isset($_POST['new_password'])){
    // User clicked the recovery link
    // we ask the security question and the new password
    include "includes/recovery_2_password_form.php";
} else if (isset($_POST['link']) && isset($_POST['new_password'])){
    // User provided the new password
    include "includes/recovery_3_password_change.php";
} else {
    error_page(400, "Malformed recovery link. Please retry.");
}

?>
<?php

include  "includes/functions.php";
include "includes/sessionUtil.php";

if(!isset($_POST['name'])||
        !isset($_POST['password'])||
        !isset($_POST['email'])||
        !isset($_POST['answer'])||
        !isset($_POST['customedQuestion'])||
        !isset($_POST['secretQuestion'])||
        !isset($_POST['g-recaptcha-response'])){

    $error_code=400;
    $error_msg="Provide all parameters.";
    include "includes/error.php";
    exit;

}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$answer = $_POST['answer'];
$customedQuestion = $_POST['customedQuestion'];
$secretQuestion = $_POST['secretQuestion'];



// Email validation
if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
    $error_code = 400;
    $error_msg = "Email is not valid";
    include "includes/error.php";
    exit;
}

// Password security validation
if ( !preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,127}$/", $password)){
    $error_code = 400;
    $error_msg = "Password is not valid. A number, a lowercase and an uppercase char are needed. Password length can be 6 to 127";
    include "includes/error.php";
    exit;
}

// Name validation: filter out dangerous or safe-to-filter characters
// Human names are unpredictable (yes, Elon, I'm talking to you >.< )
if (!preg_match("/^[^\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+$/i", $name)){
    $error_code = 400;
    $error_msg = "Valid names may only contain characters and spaces";
    include "includes/error.php";
    exit;
}


// Secret question validation
if( 
    $secretQuestion != "new" && !get_question($secretQuestion)
    || $secretQuestion == "new" && $customedQuestion == ""
    )
{
    $error_code = 400;
    $error_msg = "No secret question provided.";
    include "includes/error.php";
    exit;
}

if($secretQuestion == "new"){
    $questionIndex = -1;
} else {
    $questionIndex = $secretQuestion;
}

/* Start transaction */
$mysqli->begin_transaction();

try {
    $password=password_hash($password, PASSWORD_BCRYPT);    //Password hashing using BCRYPT
    $queryText = $mysqli->prepare(      //Insert credentials in users table
        "INSERT INTO users(password,email,full_name) VALUES(?,?,?)"
    );
    $queryText->bind_param("sss", $password, $email, $name);
    if (!$result = $queryText->execute()) {
        error_log("Insert user failed: (".$result->errno.") ".$result->error);
        $mysqli->rollback();
        $error_code=500;
        $error_msg="There was an error creating this user. Please try again later.";
        include "includes/error.php";
        exit;

    }

    $queryText = $mysqli->prepare(      //To retrieve the user's id
        "select * from users where email=?"
    );
    $queryText->bind_param("s", $email);

    $queryText->execute();
    $result = $queryText->get_result();
    $userRow = $result->fetch_assoc();

    if(!$userRow){
        $mysqli->rollback();
        $error_code=500;
        $error_msg="There was an error creating this user. Please try again later.";
        include "includes/error.php";
        exit;
    }
    $userId = $userRow['id'];

    if($questionIndex==-1) {            //Insert answer to customed question into secret_answers
        $queryText = $mysqli->prepare(
            "INSERT INTO secret_answers(answer,custom_question,user_id) VALUES(?,?,?)"
        );
        $queryText->bind_param("sss", $answer,$customedQuestion,$userId);
        if (!$result = $queryText->execute()) {
            error_log("Insert custom answer failed: (".$result->errno.") ".$result->error);
            $mysqli->rollback();
            $error_code=500;
            $error_msg="There was an error creating this user. Please try again later.";
            include "includes/error.php";
            exit;
        }
    }
    else{       //Insert answer to default question into secret_answers
        $queryText = $mysqli->prepare(
            "INSERT INTO secret_answers(answer,question_id,user_id) VALUES(?,?,?)"
        );
        $queryText->bind_param("sss", $answer,$questionIndex,$userId);
        if (!$result = $queryText->execute()) {
            error_log("Insert secret answer failed: (".$result->errno.") ".$result->error);
            $mysqli->rollback();
            $error_code=500;
            $error_msg="There was an error creating this user. Please try again later.";
            include "includes/error.php";
            exit;
        }
    }

    $activation_link = create_activation_link($userId);

    if (!$activation_link){
        error_log("Error creating activation link");
        $mysqli->rollback();
        $error_code=500;
        $error_msg="There was an error creating this user. Please try again later.";
        include "includes/error.php";
        exit;
    }

    $mysqli->commit();

    send_activation_link($userId, $activation_link);
} catch (mysqli_sql_exception $exception) {
    $mysqli->rollback();

    if ($exception->getCode() == 1062){ // duplicate entry
        // notify real user
        $real_user = get_user_by_email($email);
        $msg = "Dear ".$real_user["full_name"].",\n" .
                "there was an attempt to register your email address in the " .
                "ebook store. \n " . 
                "If it was you, we would like to inform you that you already" .
                "own an account and you can recover your password from a " . 
                "link on the login form.\n" . 
                "If it wasn't you, then someone is trying to hack into your " .
                "account. You should not click any suspect links or send any " .
                "of the links received from the Ebook Store to another " .
                "person.\n" .
                "Thank you for using the Ebook Store,\n" .
                "one of our automated penguins";
        mail($email, "Ebook Store: Security alert", $msg);
        // continue as if nothing happened
    } else {
        error_log("SQL Error creating user(".$exception->getCode()."): ".$exception->getMessage());
        $error_code=500;
        $error_msg="There was an error creating this user. Please try again later.";
        include "includes/error.php";
        exit;
    }
}

// Captcha
$userResponse=$_POST['g-recaptcha-response'];

$fields_string = '';
$fields = array(
            'secret' => '6LdDwBUaAAAAACkdiBc9YlDpTnKwbJe9OnpHugWi',
            'response' => $userResponse
        );
foreach ($fields as $key => $value)
    $fields_string .= $key . '=' . $value . '&';
$fields_string = rtrim($fields_string, '&');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

$res = curl_exec($ch);
curl_close($ch);
$result= json_decode($res, true);
if (!$result['success']) {
    $error_code = 400;
    $error_msg = "Captcha was not correctly solved";
    include "includes/error.php";
    exit;
}


?>


<!DOCTYPE html>
<html>

<head>
    <title> E-book shop </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <h1>Registration saved. We sent you an email to activate your account.</h1>
</body>

</html>

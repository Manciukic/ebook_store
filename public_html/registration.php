<?php

include  "includes/functions.php";
include "includes/sessionUtil.php";

if(!isset($_POST['name'])||
        !isset($_POST['password'])||
        !isset($_POST['email'])||
        !isset($_POST['answer'])||
        !isset($_POST['customedQuestion'])){

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
$defaultQuestion = $_POST['defaultQuestion'];

switch ($defaultQuestion) {
        case "What's your mother's last name?":
            $questionIndex=0;
    break;
        case "In what city did your parents meet?":
            $questionIndex=1;
    break;
        case "What was the name of your primary school?":
            $questionIndex=2;
    break;
        default:
            $questionIndex=-1;
    }

if(($customedQuestion =="" && $questionIndex==-1)|| ($customedQuestion !="" && $questionIndex!=-1))  {      //Check wether the user has selected only one secret question

    $error_code=400;
    $error_msg="No secret answer provided.";
    include "includes/error.php";
    exit;

}
/* Start transaction */
$mysqli->begin_transaction();

try {
    $queryText = $mysqli->prepare(      //Insert credentials in users table
        "INSERT INTO users(password,email,full_name) VALUES(?,?,?)"
    );
    $queryText->bind_param("sss", $password,$email,$name);
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
        $mysqli->commit();
    }
} catch (mysqli_sql_exception $exception) {
    $mysqli->rollback();

    throw $exception; // TODO ?
}

session_start();
setSession($email, $userId);
header('location: ./index.php');

?>

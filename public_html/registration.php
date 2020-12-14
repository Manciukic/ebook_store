<?php

include  "includes/functions.php";
include "includes/sessionUtil.php";

if(!isset($_POST['username'])||
    !isset($_POST['name'])||
    !isset($_POST['password'])||
    !isset($_POST['email'])||
    !isset($_POST['answer'])||
    !isset($_POST['customedQuestion'])){

        header('location: includes/error.php');

}

$username = $_POST['username'];
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

    header('location: includes/error.php');

}

$queryText = $mysqli->prepare(      //Insert credentials in users table
    "INSERT INTO users(username,password,email,full_name) VALUES(?,?,?,?)"
);
$queryText->bind_param("ssss", $username,$password,$email,$name);
if (!$result = $queryText->execute()) {
    echo "Execute failed: (" . $result->errno . ") " . $result->error;
}

$queryText = $mysqli->prepare(      //To retrieve the user's id
    "select * from users where username=?"
);
$queryText->bind_param("s", $username);

$queryText->execute();
$result = $queryText->get_result();
$userRow = $result->fetch_assoc();

if(!$userRow){
    header('location: includes/error.php');
}
$userId = $userRow['id'];

if($questionIndex==-1) {            //Insert answer to customed question into secret_answers
    $queryText = $mysqli->prepare(
        "INSERT INTO secret_answers(answer,custom_question,user_id) VALUES(?,?,?)"
    );
    $queryText->bind_param("sss", $answer,$customedQuestion,$userId);
    if (!$result = $queryText->execute()) {
        echo "Execute failed: (" . $result->errno . ") " . $result->error;
    }
}
else{       //Insert answer to default question into secret_answers
    $queryText = $mysqli->prepare(
        "INSERT INTO secret_answers(answer,question_id,user_id) VALUES(?,?,?)"
    );
    $queryText->bind_param("sss", $answer,$questionIndex,$userId);
    if (!$result = $queryText->execute()) {
        echo "Execute failed: (" . $result->errno . ") " . $result->error;
    }
}

session_start();
setSession($username, $userId);
header('location: ./index.php');

?>

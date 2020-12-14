<?php

    include "includes/functions.php";
    include "includes/sessionUtil.php";
 
    $username = $_POST['username'];
    $password = $_POST['password'];

    $queryText = $mysqli->prepare(
            "SELECT *
                FROM users 
                WHERE username = ? and password = ? "
            );
    $queryText->bind_param("ss", $username,$password);

    $queryText->execute();
    $result = $queryText->get_result();

    $numRow = mysqli_num_rows($result);
    if ($numRow == 0)
   	header('location: ./login_form.php?errorMessage=Your credentials are invalid.');
    else{
        $userRow = $result->fetch_assoc();
        $userId = $userRow['id'];
        session_start();
        setSession($username, $userId);
        header('location: ./index.php');
    }
?>

<?php

    include "includes/functions.php";
    include "includes/sessionUtil.php";
 
    $email = $_POST['email'];
    $password = $_POST['password'];

    $queryText = $mysqli->prepare(
            "SELECT *
                FROM users 
                WHERE email = ? and password = ? "
            );
    $queryText->bind_param("ss", $email, $password);

    $queryText->execute();
    $result = $queryText->get_result();

    $numRow = mysqli_num_rows($result);
    if ($numRow == 0)
   	    header('location: ./login_form.php?error=invalid');
    else{
        $userRow = $result->fetch_assoc();
        $userId = $userRow['id'];
        session_start();
        setSession($email, $userId);
        header('location: ./index.php');
    }
?>

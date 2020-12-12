<?php
    if (!isset($error_code))
        $error_code = 500;

    if (!isset($error_msg))
        $error_msg = "We are experiencing problems. Try again later!";
    
    http_response_code($error_code);
?>
<!DOCTYPE html>
<html>

<head>
    <title> Error <?php echo $error_code ?> </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <h1><?php echo $error_code ?></h1>
    <h2><?php echo $error_msg ?></h2>
</body>

</html>
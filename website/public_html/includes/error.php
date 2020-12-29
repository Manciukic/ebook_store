<?php
    if (!isset($error_code))
        $error_code = 500;

    if (!isset($error_msg))
        $error_msg = "We are experiencing problems. Try again later!";
    
    if (!isset($error_title)){
        $error_title = "Error ".$error_code;
    }

    http_response_code($error_code);
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $error_title ?></title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="error-page">
        <h1><?php echo $error_title ?></h1>
        <?php echo $error_msg ?>
    </main>
</body>

</html>

<?php
// Forcefully exit in case caller forgot to
exit;
?>
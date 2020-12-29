<?php

/*
 * Shows error page and exits 
 */
function error_page($error_code, $error_msg){
    $error_title = "Error ".$error_code;
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
    // Forcefully exit
    exit;
}
?>
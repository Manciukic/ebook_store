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

function fatal_error_page(){
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Internal Server Error</title>
        <?php include "includes/include.php" ?>
    </head>

    <body>
        <header>

        <a href="index.php">
            <h1>
                SecureLibrary
            </h1>
            <h2>
                Your favourite books. Anywhere, anytime
            </h2>
        </a>
        </header>
        <main class="error-page">
            <h1>Error 500</h1>
            <p>There was an error. Please retry later.</p>
        </main>
    </body>

    </html>
<?php
}

function shutdown_handler(){
    $lasterror = error_get_last();
    if ($lasterror === null || !isset($lasterror['type']))
        return;
    switch ($lasterror['type'])
    {
        case E_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_PARSE:
            fatal_error_page();
    }
}

register_shutdown_function("shutdown_handler");
?>
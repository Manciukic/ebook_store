<?php
require_once "includes/functions.php";

session_start();
if(isset($_SESSION['user_id'])){
    $user = get_user($_SESSION['user_id']);
} else {
    // TODO gestire errore
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        Order #<?php echo $order_id_str ?>
    </title>
    <?php include "includes/include.php" ?>
</head>
<body>
    <?php include "includes/header.php" ?>
    <main class="profile-page">
        <?php echo $user['username']; ?>
        <?php echo $user['full_name']; ?>
        <?php echo $user['email']; ?>
    </main>
</body>
</html>
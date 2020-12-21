<?php
require_once "includes/functions.php";
session_start();

if (isset($_SESSION['user_id'])) {
    $user = get_user($_SESSION['user_id']);
} else {
    $error = true;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        User profile
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="profile-page">
        <?php if (isset($error) && $error) { ?>
            <h1>Not logged in</h1>
            <div class="profile-links">
                <a href="login_form.php">
                    Login
                </a>
            </div>
        <?php
        } else {
        ?>
            <h1>
                User profile: <?php echo $user['full_name']; ?>
            </h1>
            <h2>
                <?php echo $user['full_name']; ?> - <?php echo $user['email']; ?>
            </h2>

            <div class="profile-links">
                <div class="profile-links-group">
                    <a href="orders.php">
                        View all orders
                    </a>
                    <a href="library.php">
                        Your library
                    </a>
                </div>
                <div class="profile-links-group">
                    <a href="password_change.php">
                        Change password
                    </a>
                </div>
            </div>
        <?php
        }
        ?>
    </main>
</body>

</html>
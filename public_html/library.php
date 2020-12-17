<?php
require_once "includes/functions.php";
session_start();

if (isset($_SESSION['user_id'])) {
    $user = get_user($_SESSION['user_id']);
} else {
    $error = true;
    $error_code = 401; // Unauthorized
    $error_title = "Not logged in";
    ob_start();
?>
    <div class="profile-links">
        <a href="login_form.php">
            Login
        </a>
    </div>
<?php
    $error_msg = ob_get_clean();
    include "includes/error.php";
    exit;
}

$ebooks = get_all_ebooks($_SESSION['user_id']);
if (!$ebooks) {
    include "includes/error.php";
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>
        User library
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="cart-page">
        <h1>
            <?php echo $user['username']; ?>'s library
        </h1>
        <table class="cart-table">
            <tr>
                <th>
                    Title
                </th>
                <th>
                    Author
                </th>
                <th>
                </th>
                <th>
                </th>
                <th>
                </th>
            </tr>
            <?php
            $n_ebooks = 0;
            while ($ebook = $ebooks->fetch_array()) {
                $n_ebooks += 1; ?>
                <tr>
                    <td><?php echo $ebook["title"] ?></td>
                    <td><?php echo $ebook["author"] ?></td>
                    <td class="cart-download">
                        <a href="order.php?id=<?php echo $ebook['order_id'] ?>">
                            Order details &#187;</a>
                    </td>
                    <td class="cart-download">
                        <a href="book.php?id=<?php echo $ebook['ebook_id'] ?>">
                            Book details &#187;</a>
                    </td>
                    <td class="cart-download"><a href="download.php?id=<?php echo $ebook['ebook_id'] ?>">
                        Download <span class="dl-icon">&#11015;</span></a>
                    </td>
                </tr>
            <?php }
            if ($n_ebooks == 0) { ?>
                <tr>
                    <td colspan="5"> No ebooks have been bought yet</td>
                </tr>
            <?php } ?>
        </table>
    </main>
</body>

</html>
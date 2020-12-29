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

$orders = get_orders($_SESSION['user_id']);
if (!$orders) {
    include "includes/error.php";
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>
        User orders
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="cart-page">
        <h1>
            <?php echo $user['full_name']; ?>'s orders
        </h1>
        <table class="table">
            <tr>
                <th>
                    Order
                </th>
                <th>
                    Date
                </th>
                <th>
                    Paid with
                </th>
                <th>
                </th>
                <th class="cart-price">
                    Price
                </th>
            </tr>
            <?php
            $n_orders = 0;
            while ($order = $orders->fetch_array()) {
                $n_orders += 1; ?>
                <tr>
                    <td>Order #<?php echo $order["id"] ?></td>
                    <td><?php echo date("d-m-Y H:i:s", $order["time"]); ?></td>
                    <td>**** **** **** <?php echo $order["cc_last_digits"] ?></td>
                    <td class="cart-link">
                        <a href="order.php?id=<?php echo $order['id'] ?>">
                            Details &#187;</a>
                    </td>
                    <td class="cart-price"><?php echo number_format($order["price"], 2); ?> &#8364;</td>
                </tr>
            <?php }
            if ($n_orders == 0) { ?>
                <tr>
                    <td colspan="5"> No orders have been completed yet</td>
                </tr>
            <?php } ?>
        </table>
    </main>
</body>

</html>
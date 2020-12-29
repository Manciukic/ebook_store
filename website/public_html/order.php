<?php
session_start();
require_once "includes/functions.php";
require_once "includes/error.php";

if (!isset($_GET["id"])){
    error_page(400, "No order ID provided");
}

if (!isset($_SESSION["user_id"])){
    error_page(403, "You're not logged in");
}

$order_query_result = get_order($_GET["id"], $_SESSION["user_id"]);

if ($order_query_result->num_rows === 0){
    error_page(404, "Order not found");
}

$order = $order_query_result->fetch_array();

$items = get_ebooks_from_order($order["id"]);
$order_id_str = str_pad($order["id"], 6, "0", STR_PAD_LEFT);
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
    <main class="cart-page">
        <h1>
            Order #<?php echo $order_id_str ?> 
            (<?php echo date("H:i:s d-m-Y", $order["time"]); ?>)
        </h1>
        <table class="table">
            <tr>
                <th>
                    Title
                </th>
                <th>
                    Author
                </th>
                <th>
                </th>
                <th class="cart-price">
                    Price
                </th>
            </tr>
            <?php while ($row = $items->fetch_array()) { ?>
                <tr>
                    <td class="cart-title"><?php echo $row['title']; ?></td>
                    <td class="cart-author"><?php echo $row['author']; ?></td>
                    <td class="cart-link"><a href="download.php?id=<?php echo $row['id'] ?>">
                        Download <span class="dl-icon">&#11015;</span></a>
                    </td>
                    <td class="cart-price">
                        <?php
                        echo number_format($row['price'], 2);
                        ?>
                        &#8364;
                    </td>
                </tr>
            <?php } ?>
        </table>
        <div class="cart-total">
            <p class="cart-total-message">
                Total:
                <span class="cart-total-number">
                    <?php echo number_format($order["price"], 2); ?>
                </span>
                &#8364;
            </p>
            <p>
                Paid with **** **** **** <?php echo $order["cc_last_digits"] ?>
            </p>
        </div>
    </main>
</body>

</html>
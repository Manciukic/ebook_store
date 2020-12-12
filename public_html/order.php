<?php
session_start();
include "includes/db_connect.php";

if (!isset($_GET["id"])){
    include "includes/error.php";
    return;
}

if (!isset($_SESSION["user_id"])){
    include "includes/error.php";
    return;
}

$order_query = $mysqli->prepare("
        SELECT O.id AS id, UNIX_TIMESTAMP(O.time) AS time, O.price AS price, SUBSTRING(CC.number, 12, 4) AS cc_last_digits
        FROM orders O
            INNER JOIN credit_cards CC ON (O.credit_card_id = CC.id)
        WHERE O.id=? AND O.user_id=?
    ");
$order_query->bind_param("ii", $_GET["id"], $_SESSION["user_id"]);
$order_query->execute();
$order_query_result = $order_query->get_result();

if ($order_query_result->num_rows === 0){
    include "includes/error.php";
    return;
}

$order = $order_query_result->fetch_array();

$order_ebook_query = $mysqli->prepare("
        SELECT E.id AS id, E.title AS title, E.author AS author, OE.price AS price
        FROM ebooks E 
            INNER JOIN order_ebook OE ON (E.id = OE.ebook_id)
        WHERE OE.order_id=?
    ");
$order_ebook_query->bind_param("i", $order["id"]);
$order_ebook_query->execute();
$items = $order_ebook_query->get_result();
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
        <table class="cart-table">
            <tr>
                <th>
                    Title
                </th>
                <th>
                    Author
                </th>
                <th class="cart-price">
                    Price
                </th>
                <th>
                    Download
                </th>
            </tr>
            <?php while ($row = $items->fetch_array()) { ?>
                <tr>
                    <td class="cart-title"><?php echo $row['title']; ?></td>
                    <td class="cart-author"><?php echo $row['author']; ?></td>
                    <td class="cart-price">
                        <?php
                        echo number_format($row['price'], 2);
                        ?>
                        &#8364;
                    </td>
                    <td class="cart-download"><a href="download.php?id=<?php echo $row['id'] ?>">Download</a></td>
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
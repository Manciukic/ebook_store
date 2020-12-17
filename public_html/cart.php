<?php
session_start();
require_once "includes/functions.php";

if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = array();
}

if (isset($_GET['add']) && is_numeric($_GET['add'])) {
    // Adding a new item to the cart
    $book_id = $_GET['add'];
    if (!in_array($book_id, $_SESSION['items'])) {
        array_push($_SESSION['items'], $book_id);
    }
}

if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    // Removing a new item from the cart
    $book_id = $_GET['remove'];
    $index = array_search($book_id, $_SESSION['items']);
    if ($index !== false) {
        unset($_SESSION['items'][$index]);
    }
}

// Create a prepared statement based on the number of books I need
$book_ids = $_SESSION['items'];
$cart = get_books($book_ids);
$cart_total = 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        Cart
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="cart-page">
        <h1>Your current cart</h1>
        <table class="cart-table">
            <tr>
                <th class="cart-remove"></th>
                <th>
                    Title
                </th>
                <th>
                    Author
                </th>
                <th class="cart-price">
                    Price
                </th>
            </tr>

            <?php
            if ($cart) {
                while ($row = $cart->fetch_array()) { ?>
                    <tr>
                        <td class="cart-remove"><a href="cart.php?remove=<?php echo $row['id']; ?>">&#10006;</a></td>
                        <td class="cart-title"><?php echo $row['title']; ?></td>
                        <td class="cart-author"><?php echo $row['author']; ?></td>
                        <td class="cart-price">
                            <?php
                            $cart_total += $row['price'];
                            echo number_format($row['price'], 2);
                            ?>
                            &#8364;
                        </td>
                    </tr>
            <?php }
            } 
            
            if($cart_total == 0){ ?>
            <tr>
                <td colspan="4">Cart is empty</td>
            </tr>
            <?php }
            ?>

        </table>
        <div class="cart-total">
            <p class="cart-total-message">
                Current total:
                <span class="cart-total-number">
                    <?php echo number_format($cart_total, 2); ?>
                </span>
                &#8364;
            </p>
            <?php if (!empty($book_ids)){ ?>
                <div class="cart-buy-button">
                    <a href="checkout.php">
                        Buy now!
                    </a>
                </div>
            <?php } ?>
        </div>
    </main>
</body>

</html>
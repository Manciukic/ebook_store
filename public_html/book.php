<?php
require_once "includes/functions.php";

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $book = get_book($book_id);
    if (!$book) {
        $error_code=404;
        $error_msg="Book ".$_GET['id']." not found";
        include "includes/error.php";
        exit;
    }
    $genre_result = get_book_genres($book_id);
} else {
    $error_code=400;
    $error_msg="No book ID provided";
    include "includes/error.php";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        <?php echo $book['title'] ?>
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="book-page">
        <h1 class="book-title"><?php echo $book['title'] ?></h1>
        <h2 class="book-author"><span class="book-author-name">
                <?php echo $book['author'] ?>        
            </span>
        </h2>
        <ul class="book-genres">
            <?php while($genre = $genre_result->fetch_array()){ ?>
            <li>
                <a href="<?php echo 'search.php?genre='.$genre['id']; ?>">
                    <div class="book-genre">
                        <?php echo $genre['name']; ?>
                    </div>
                </a>
            </li>
            <?php
            }
            ?>
        </ul>
        <a href="cart.php?add=<?php echo $book['id']; ?>">
            <div class="book-buy">
                Buy it for <?php echo number_format($book['price'], 2) ?> &#8364;
            </div>
        </a>
        <div class="description">
            <?php echo $book['description'] ?>
        </div>
    </main>
</body>

</html>
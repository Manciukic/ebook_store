<?php
require_once "includes/functions.php";
require_once "includes/error.php";

session_start();

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $book = get_book($book_id);
    if (!$book) {
        error_page(404, "Book " . $_GET['id'] . " not found");
    }
    $genre_result = get_book_genres($book_id);
} else {
    error_page(400, "No book ID provided");
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
            <?php while ($genre = $genre_result->fetch_array()) { ?>
                <li>
                    <a href="<?php echo 'search.php?genre=' . $genre['id']; ?>">
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
            <?php
                $book_path = false;
                if(isset($_SESSION['user_id'])){
                    $book_path = path_to_ebook_auth($_SESSION['user_id'], $book['id']);
                }
            ?>
            <div class="book-buy-download">
                <?php
                if (!$book_path) { ?>
                    Buy it for <?php echo number_format($book['price'], 2) ?> &#8364;
                <?php
                } else {
                ?>
                    <a href="download.php?id=<?php echo $book['id'] ?>">
                        Download <span class="dl-icon">&#11015;</span>
                    </a>
                <?php } ?>
            </div>
        </a>
        <div class="description">
            <?php echo htmlspecialchars($book['description']) ?>
        </div>
    </main>
</body>

</html>
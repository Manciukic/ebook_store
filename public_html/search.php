<?php
require_once "includes/functions.php";


if (isset($_GET['genre'])) {
    $genre_id = $_GET['genre'];

    $result = get_by_genre($genre_id);
    $genre = get_genre_name($genre_id);
    if (!$genre) {
        include "includes/error.php";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        Books by genre "<?php echo $genre['name'] ?>"
    </title>
    <?php include "includes/include.php" ?>
</head>

<body>
    <?php include "includes/header.php" ?>
    <main class="search-page">
        <h1>
            Books by genre "<?php echo $genre['name'] ?>"
        </h1>
        <ul class="search-results">
            <?php
            if ($result) {
                while ($row = $result->fetch_array()) {
            ?>
                    <li>
                        <a href="<?php echo 'book.php?id=' . $row['id']; ?>">

                            <div class="book-search-entry">
                                <p class="book-search-title">
                                    <?php echo $row['title'] ?>
                                </p>
                                <p class="book-search-author">
                                    <?php echo $row['author'] ?>
                                </p>
                                <p class="book-search-price"><span class="book-price-number"> <?php echo number_format($row['price'], 2) ?></span> &#8364; </p>
                            </div>
                        </a>
                    </li>
            <?php
                }
            }
            ?>
        </ul>
    </main>
</body>

</html>
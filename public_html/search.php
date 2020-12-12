<?php
include "db_connect.php";

if (isset($_GET['genre'])) {
    $genre_id = $_GET['genre'];

    $query = $mysqli->prepare(
        "SELECT B.id, B.title, B.author, B.price
            FROM ebooks B INNER JOIN ebook_genre EG ON B.id = EG.ebook_id
            WHERE EG.genre_id = ? "
    );
    $query->bind_param("i", $genre_id);
    $query->execute();
    $result = $query->get_result();

    $genre_query = $mysqli->prepare(
        "SELECT G.name
            FROM genres G 
            WHERE G.id = ?"
    );
    $genre_query->bind_param("i", $genre_id);
    $genre_query->execute();
    $genre_result = $genre_query->get_result();
    $genre = $genre_result->fetch_array();
    if (!$genre) {
        include "error.php";
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
    <?php include "include.php" ?>
</head>

<body>
    <?php include "header.php" ?>
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
<?php
include "db_connect.php";

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    $book_query = $mysqli->prepare(
        "SELECT *
            FROM ebooks B
            WHERE B.id = ? "
    );
    $book_query->bind_param("i", $book_id);
    $book_query->execute();
    $book_result = $book_query->get_result();
    $book = $book_result->fetch_array();
    if (!$book) {
        include "error.php";
        exit;
    }

    $genre_query = $mysqli->prepare(
        "SELECT G.id, G.name
            FROM ebook_genre EG INNER JOIN genres G
            ON EG.genre_id = G.id 
            WHERE EG.ebook_id = ?"
    );
    $genre_query->bind_param("i", $book_id);
    $genre_query->execute();
    $genre_result = $genre_query->get_result();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        <?php echo $book['title'] ?>
    </title>
    <?php include "include.php" ?>
</head>

<body>
    <?php include "header.php" ?>
    <main class="book-page">
        <h1 class="book-title"><?php echo $book['title'] ?></h1>
        <h2 class="book-author"><span class="book-author-name">
                <?php echo $book['author'] ?>        
            </span>
        </h2>
        <ul class="book-genres">
            <?php while($genre = $genre_result->fetch_array()){ ?>
            <li>
                <a href="<?php echo '/search.php?genre='.$genre['id']; ?>">
                    <div class="book-genre">
                        <?php echo $genre['name']; ?>
                    </div>
                </a>
            </li>
            <?php
            }
            ?>
        </ul>
        <a href="#">
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
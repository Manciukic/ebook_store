<?php
include "db_connect.php";

if (isset($_GET['genre'])) {
    $genre = $_GET['genre'];

    $query = $mysqli->prepare(
        "SELECT *
            FROM ebooks B JOIN ebook_genre EG ON B.id = EG.ebook_id
            WHERE EG.genre_id = ? "
    );
    $query->bind_param("i", $genre);
    $query->execute();
    $result = $query->get_result();
}
?>

<DOCTYPE! html>
    <html>

    <head>
        <title>
            Search results for "<?php echo $genre ?>"
        </title>
    </head>

    <body>
        <header>
            <h1>
                Search results for "<?php echo $genre ?>"
            </h1>
        </header>
        <?php include "navbar.php" ?>
        <main>
            <ul>
                <?php
                if ($result) {
                    while ($row = $result->fetch_array()) {
                ?>
                        <li>
                            <div class="book-entry">
                                <h3>
                                    <?php echo $row['title'] ?>
                                </h3>
                                <h4>
                                    <?php echo $row['author'] ?>
                                </h4>
                            </div>
                        </li>
                <?php
                    }
                } 
                ?>
            </ul>
        </main>
    </body>

    </html>
<nav>
    <ul>
        <?php
        $genres = $mysqli->query("SELECT * from genres");
        if ($genres) {
            while ($row = $genres->fetch_array()) {
        ?>
                <li class='menu-entry'>
                    <a href="<?php echo "/search.php?genre=" . $row['id'] ?>">
                        <?php echo $row['name'] ?>
                    </a>
                </li>
        <?php
            }
        }
        ?>
    </ul>
</nav>
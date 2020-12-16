<nav>
    <ul>
        <?php
        $genres = get_all_genres();
        if ($genres) {
            while ($row = $genres->fetch_array()) {
        ?>
                <li class='menu-entry'>
                    <a href="<?php echo "search.php?genre=" . $row['id'] ?>">
                        <?php echo $row['name'] ?>
                    </a>
                </li>
        <?php
            }
        }
        ?>
    </ul>
    <div class="menu-cart">
        <a href="profile.php">
            Profile
        </a>
        <a href="cart.php">
            View cart
        </a>
    </div>
</nav>
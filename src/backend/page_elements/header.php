<nav class="navbar">
    <a href="/" class="navbar-brand">
        <!-- <img src="..." alt="..."> -->
        Your Brand
    </a>

    <?php
        if($backend->is_logged_in()) {
            $user = $backend->get_current_user();
            ?>
                <span class="navbar-text ml-auto mr-20"> <!-- ml-auto = margin-left: auto -->
                    <div> 
                        <span class='navbar-text'>Hello, <?php echo $user->username ?>!</span>
                    </div>
                </span>
            <?php
            echo '<a href="/backend/requests/logout.php?redirect=/"><button class="btn btn-primary">Logout</button></a>';
        }
    ?>
</nav>
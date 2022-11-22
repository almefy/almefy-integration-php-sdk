<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almefy Integration Sample</title>

    <link href="https://cdn.jsdelivr.net/npm/halfmoon@1.1.1/css/halfmoon-variables.min.css" rel="stylesheet" />
</head>
<body>

    <!-- Load $backend and $almefy objects -->
    <?php require_once __DIR__ . "/backend/loadbackend.php"; ?>

    <div 
        class="page-wrapper with-navbar with-navbar-fixed-bottom" 
        data-sidebar-type="overlayed-sm-and-down">
        
        <!-- Navbar -->
        <?php require_once __DIR__ . "/backend/page_elements/header.php" ?>
        
        <!-- Sidebar overlay -->
        <!-- <div class="sidebar-overlay" onclick="halfmoon.toggleSidebar()"></div> -->
        <!-- Sidebar -->
        <!-- <div class="sidebar"> -->
        <!-- ... -->
        <!-- </div> -->

        <!-- Content wrapper -->
        <div class="content-wrapper container">

            <div class="content">
                <?php require_once __DIR__ . "/backend/page_elements/alert.php" ?>
            </div>

            <div class="content">
                <?php if(!$backend->is_logged_in()): ?>
                    <!-- logged off -->
                    <?php require_once __DIR__ . "/backend/page_elements/login_page.php" ?>
                <?php else: ?>
                    <!-- logged in -->
                    <?php require_once __DIR__ . "/backend/page_elements/profile.php" ?>
                    
                <?php endif; ?>
            </div>
        </div>

        <!-- Navbar fixed bottom -->
        <nav class="navbar navbar-fixed-bottom">
            <span class="navbar-text"> <!-- ml-auto = margin-left: auto -->
                <div> 
                    &copy; <a href="https://almefy.com">Almefy GmbH</a>
                </div>
            </span>

            <span class="navbar-text ml-auto"> <!-- ml-auto = margin-left: auto -->
                <div> 
                    <!-- TODO add github url -->
                    This is an example found at <a href="https://almefy.com">Github</a>                
                </div>
            </span>
        </nav>
    </div>
    
    <?php require_once __DIR__ . "/backend/page_elements/footer.php" ?>
</body>
</html>
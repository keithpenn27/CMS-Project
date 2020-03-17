<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "inc/config.php"; 

    require_once "inc/header.php";

    Utils\Page::forceDashboard();
    
?>

        <div class="container">
        <div class="jumbotron">
            <?php
                if (isset($_GET['message']) && $_GET['message'] != null):
            ?>
                  <h3><?php echo str_replace('%20', ' ', $_GET['message']); ?></h3> 
                <?php else: ?>
                  <h3>Welcome To CMS Project!</h3>    
                <?php endif; ?>
                </div>
                <div class="row">
                <p>The CMS Project is a content managemet system that allows users to write blog posts, upload music, and share photos! <a href="<?php echo __PATH__ . "register/"?>">Sign up </a> today to get started!</p>
            </div>
        </div>

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "inc/config.php"; 

    require_once "inc/header.php";
    
?>

        <div class="container">
            <?php
                if (isset($_GET['message']) && $_GET['message'] != null):
            ?>
            <div class="jumbotron">
                  <h3><?php echo str_replace('%20', ' ', $_GET['message']); ?>      
            </div>
                <?php endif; ?>
        </div>

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
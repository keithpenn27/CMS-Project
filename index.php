<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "inc/config.php"; 

    require_once "inc/header.php";
    
?>

        <div class="container">
            <?php
                echo "Hello World! Today is: ";
                echo date('Y m d');
            ?>
        </div>

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
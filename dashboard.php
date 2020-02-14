<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "inc/config.php";

    Page::ForceLogin();

    $user = new User($_SESSION['user_id']);

    require_once "inc/header.php";
?>
    <div class="container">
      <p>Hello <?php echo $user->email; ?>, you registered at <?php echo $user->reg_time; ?></p>
    </div>

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
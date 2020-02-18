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
      <?php if (isset($_GET['message']) && $_GET['message'] != null): ?>
      <div class="alert alert-dismissible alert-success">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <p><?php echo $_GET['message'] ?></p>
      <?php endif; ?>

    </div>
    </div>

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "inc/config.php";

    Page::ForceLogin();

    $user = new User($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE-edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="robots" content="follow">

        <title>Page Title</title>

        <base href="cms-project" />
        <link rel="stylesheet" href="css/bootstrap.css" crossorigin="anonymous">
    </head>

    <body>
    <div class="container">
      <p>Hello <?php echo $user->email; ?>, you registered at <?php echo $user->reg_time; ?></p>
      <a href="logout.php">Logout</a>
    </div>

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
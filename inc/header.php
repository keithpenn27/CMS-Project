<?php
      if(!defined('__CONFIG__')) {
        exit('You do not have a config file.');
    }

    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != null) {
      $user = new User($_SESSION['user_id']);
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE-edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="robots" content="follow">

        <title>CMS Project</title>

        <base href="cms-project" />
        <link rel="stylesheet" href="css/bootstrap.css" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css" crossorigin="anonymous">
    </head>
    <body>

    <?php
      require_once "navbar.php";
    ?>
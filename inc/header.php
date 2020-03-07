<?php

      if(!defined('__CONFIG__')) {
        header('Location: ' . __PATH__);
        exit;

    }

    require_once "config.php";

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
        <link rel="stylesheet" href=<?php echo __PATH__ . "css/bootstrap.css"; ?> crossorigin="anonymous">
        <link rel="stylesheet" href=<?php echo __PATH__ . "css/style.css"; ?> crossorigin="anonymous">

    </head>
    <body>

    <?php
      require_once "navbar.php";
    ?>
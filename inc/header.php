<?php
      if(!defined('__CONFIG__')) {
        header('Location: ../index.php');
        exit;

    }

    require_once "classes/Url.php";

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
        <link rel="stylesheet" href=<?php echo Url::getBasePath() . "css/bootstrap.css"; ?> crossorigin="anonymous">
        <link rel="stylesheet" href=<?php echo Url::getBasePath() . "css/style.css"; ?> crossorigin="anonymous">

    </head>
    <body>

    <?php
      require_once "navbar.php";
    ?>
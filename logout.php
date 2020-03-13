<?php
    session_start();
    session_destroy();
    session_write_close();
    setcookie(session_name() . '' . 0 . '/');

    $_GET['message'] = str_replace(' ', '%20', "You have been logged out. Come back soon!");

    $query = http_build_query($_GET);

    $location = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . basename(dirname(__FILE__)) . '/' . "?" . $query;

    header("Location: " . $location);
?>
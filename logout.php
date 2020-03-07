<?php
    session_start();
    session_destroy();
    session_write_close();
    setcookie(session_name() . '' . 0 . '/');
    session_regenerate_id(true);

    $_GET['message'] = str_replace(' ', '%20', "You have been logged out. Come back soon!");

    $query = $query = http_build_query($_GET);

    $location =  (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . "/cms-project/" . "?" . $query;

    var_dump($location);

    header("Location: " . $location);
?>
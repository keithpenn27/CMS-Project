<?php

 // Constant for the base url. This way if the name of the project folder is different it shouldn't effect slugs.
 define('__PATH__', (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . basename(dirname(__FILE__, 2)) . '/');

 // If there is no constant defined called __CONFIG__ do not load this file
    if(!defined('__CONFIG__')) {
        header('Location: ' . __PATH__);
        exit;

    }

     // Sessions are always turned on
     if (!isset($_SESSION)) {
        session_start();
}
    // Our config is below

	// Include the class files
	include_once "classes/DB.php";
    include_once "classes/Filter.php";
    include_once "classes/User.php";
    include_once "classes/Page.php";
    include_once "classes/FileHandler.php";
    include_once "classes/Blog.php";
    include_once "functions.php";

    // Set up our db connection
    $host = "db-Host";
    $port = "db-Port";
    $dbName = "db-Name";
    $dbUserName = "db-UserName";
    $dbPass = "db-Pass";

    $tableCon = DB::setConnection($host, $port, $dbName, $dbUserName, $dbPass);
    $con = DB::getConnection();
    
    ?>

<?php

    // If there is no constant defined called __CONFIG__ do not load this file
    if(!defined('__CONFIG__')) {
        exit('You do not have a config file.');
    }

    // Our config is below

	// Include the class files
	include_once "classes/DB.php";
	include_once "classes/Filter.php";

    $con = DB::getConnection();
    
    ?>
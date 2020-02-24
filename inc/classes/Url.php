<?php

 // If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ../index.php');
    exit;

}

class Url {

    public static function getBasePath() {
        // Constant for the base url
        return "/cms-project/";
    }
}

?>
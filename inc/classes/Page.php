<?php
namespace Utils;

 // If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ' . __PATH__);
    exit;

}

class Page {
    // Force the user to be logged in, or redirect
    public static function ForceLogin() {
        if (isset($_SESSION['user_id'])) {
            // The user is allowed here
        } else {
            // The user is not allowed here
            header("Location: " . __PATH__ . "login/"); exit;
        }
    }

    public function ForceDashboard() {
        if (isset($_SESSION['user_id'])) {
            // The user is allowed here, but redirect anyway
            header("Location: " . __PATH__ . "dashboard/"); exit;
        } else {
            // The user is not allowed here
        }
    }
}

?>
<?php

class DB {
    protected static $con;

    private function __construct() {
        try {
            self::$con = new PDO('mysql:charset=utf8mb4;host=localhost;port=3306;dbname=cms_project', 'kp-anamoly-27', 'JorLog1720');
            self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$con->setAttribute(PDO::ATTR_PERSISTENT, false);

        } catch (PDOException $e) {
            echo "Could not connect to database<br/>" . $e->getMessage();
            exit;
        }

    }

    public static function getConnection() {

        // If this instance has not been started, start it.
        if (!self::$con) {
            new DB();
        }

        return self::$con;
    }
}
?>
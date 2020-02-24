<?php

 // If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ../index.php');
    exit;

}

class DB {
    protected static $con;

    private function __construct($host, $port, $dbName, $dbUsername, $dbPass) {
        try {
            self::$con = new PDO('mysql:charset=utf8mb4;host=' . $host . ';port=' . $port . ';dbname=' . $dbName, $dbUsername, $dbPass);
            self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$con->setAttribute(PDO::ATTR_PERSISTENT, false);

        } catch (PDOException $e) {
            // We are not connected and we need to set up the database.
            // We need to make sure that we are not currently on the dbsetup.php page so we don't create an infinite redirect
            $uri = explode("/", $_SERVER['REQUEST_URI']);

            $uri= end($uri);

           // If not on the dbsetup.php page, redirect to it.
            if ($uri != "dbsetup.php" && $uri != "dbsubmission.php") {
                header("Location: " . Url::getBasePath() . "inc/dbsetup.php");
            }
        }

    }

    public static function getConnection() {
        return self::$con;
    }

    public static function setConnection($host, $port, $dbName, $dbUsername, $dbPass) {
        
        // If this instance has not been started, start it.
        if (!self::$con) {
            new DB($host, $port, $dbName, $dbUsername, $dbPass);
        }
    }
    
    public static function createTables($pdo) {
        $userTable = 'CREATE TABLE users(
            uid   INT  (5) UNSIGNED AUTO_INCREMENT COMMENT "User Id" NOT NULL,
            first_name VARCHAR (50)  COMMENT "The users first name"   NOT NULL,
            last_name  VARCHAR (100)  COMMENT "The users last name"  NOT NULL,
            email  VARCHAR (250) COLLATE utf8_unicode_ci COMMENT "The users email" NOT NULL ,
            password   VARCHAR (200) COMMENT "The users password" NOT Null,
            reg_time   TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT "The date and time the user registered" NOT NULL, 
            PRIMARY KEY (uid),
            UNIQUE KEY (email)
         )
         ENGINE = INNODB;';
        try {
            $ex = $pdo->prepare($userTable);
            $ex->execute();
        } catch (PDOException $e) {
            echo "Your database is already set up." . $e->getMessage();
            return;
        }
    
    }
}
?>
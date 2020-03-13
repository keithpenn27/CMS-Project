<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once  "config.php"; 

    require_once "header.php";

        // If we have a connection to the database, redirect to home page.
        if(DB::getConnection()) {
            header("Location: " . __PATH__);
            exit;
        }

    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != null) {
        $referrer = $_SERVER['HTTP_REFERER'];
        $referrer = explode('/', $referrer);
        $referrer = end($referrer);
    } else {
        header("Location: " . __PATH__ . "inc/dbsetup/");
    }
    
    if ($_SERVER["REQUEST_METHOD"] == 'POST' && $referrer == "dbsetup.php") {

            $host = $_POST['host'];
            $port = $_POST['port'];
            $dbName = $_POST['db-name'];
            $dbUserName= $_POST['db-username'];
            $dbPass = $_POST['db-password'];

            $fileName = "config.php";

        
            $configArr = array(
               "host" => "db-Host",
               "port" => "db-Port",
               "dbName" => "db-Name",
               "dbUserName" => "db-UserName",
               "dbPass" => "db-Pass"
            );

            $file = file($fileName);
            $config = str_replace($configArr["host"], $host, $file);
            file_put_contents($fileName, $config);
            $file = file($fileName);
            $config = str_replace($configArr["port"], $port, $file);
            file_put_contents($fileName, $config);
            $file = file($fileName);
            $config = str_replace($configArr["dbName"], $dbName, $file);
            file_put_contents($fileName, $config);
            $file = file($fileName);
            $config = str_replace($configArr["dbUserName"], $dbUserName, $file);
            file_put_contents($fileName, $config);
            $file = file($fileName);
            $config = str_replace($configArr["dbPass"], $dbPass, $file);
            file_put_contents($fileName, $config);

            $con = DB::setConnection($host, $port, $dbName, $dbUserName, $dbPass);
            $tableCon = DB::getConnection();

            echo "<div class=\"container\">";
            DB::createTables($tableCon);
            echo "</div>";

    }

    require_once "footer.php";
?>

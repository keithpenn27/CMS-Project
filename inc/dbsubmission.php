<?php

    // Allow the config
    define('__CONFIG__', true);

    require_once "header.php";

    // If we have a connection to the database, redirect to home page.
    if(System\DB::getConnection()) {
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

        // Set up our two arrays so we can compare and update.
        $newConfigArr = array(
            "host" => $_POST['host'],
            "port" => $_POST['port'],
            "dbName" => $_POST['db-name'],
            "dbUserName" => $_POST['db-username'],
            "dbPass" => $_POST['db-password']
        );

        $configArr = array(
            "host" => "db-Host",
            "port" => "db-Port",
            "dbName" => "db-Name",
            "dbUserName" => "db-UserName",
            "dbPass" => "db-Pass"
        );

        // Get the current config so we can rewrite without losing info
        $fileName = "config.php";

        if (is_writable($fileName)) {
        
        // Iterate through both arrays and compare the keys
        foreach($configArr as $fKey => $fVal) {
            foreach ($newConfigArr as $nKey => $nVal) {

                // If the keys match, use str_replace the default values with the user's database config
                if ($nKey == $fKey) {

                    // we need to get the file's contents each iteration so we don't lose the last iterations info.
                    $file = file($fileName);
                    $config = str_replace($fVal, $nVal, $file);
                    file_put_contents($fileName, $config);
                }
    
            }

        }

        // Since this is our first connection, we need to set the connnection apart from the config file. The config file will handle it after this.
        $tableCon = System\DB::setConnection($newConfigArr['host'], $newConfigArr['port'], $newConfigArr['dbName'], $newConfigArr['dbUserName'], $newConfigArr['dbPass']);
        $con = System\DB::getConnection();

        // If we have our connection, we set up the db tables.
        if ($con != null) {
            echo "<div class=\"container\">";
            System\DB::createTables($con);
            echo "</div>";
        }

        } else {
            echo "<div class=\"container\"><div class=\"alert alert-dismissible alert-danger\">The config.php could not be updated! Please make sure the file is writable and the proper permissions have been set.</div></div>";
        }

    }

    require_once "footer.php";
?>

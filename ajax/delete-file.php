<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "../inc/config.php"; 
    
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        // Always return json format
        header('Content-Type: application/json');
        
        $response = [];

        $fid = $_POST['fileId'];
        $fileName = $_POST['fileName'];

        try {
            $fileRow = $con->prepare("DELETE FROM files WHERE fid = :fid");
            $fileRow->bindParam(":fid", $fid, PDO::PARAM_INT);
            $fileRow->execute();

            FileHandler::deleteFile($fileName);

            $response['fileDeleted'] = true;
            $response['fid'] = $fid;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        // Return the proper information back to JavaScript to redirect us.

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;

    } else {
        // Die. Kill the scripe. Redirect the user.
        header("Location: " . __PATH__ . "index.php");
        exit('Invalid URL');
    }
?>
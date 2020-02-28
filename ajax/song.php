<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "../inc/config.php"; 
    
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        // Always return json format
        header('Content-Type: application/json');
        $response = [];

        // Get the file and check for errors
        $err = $_FILES['file']['error'];

        if ($err > 0) {
            // If error value is larger than 0, there is an error
            $response['error'] = "There was an error while uploading the file:<br/>";
        } else {

            // Create an array of the form info to pass into songUpload(). Must be associative array with column name as the key (top hierarchy)
            // and pdoVal as first element key of second level, pdoType as second element key of second level
            $formArr = array(
                ":song_title" => array(
                    "pdoVal" => Filter::String($_POST['song-title']),
                    "pdoType" => PDO::PARAM_STR
                ),
                ":artist" => array(
                    "pdoVal" => $_POST['artist'],
                    "pdoType" => PDO::PARAM_STR
                ),  
                ":album" => array(
                    "pdoVal" => $_POST['album'],
                    "pdoType" => PDO::PARAM_STR
                ),
                ":owner" => array(
                    "pdoVal" => $_SESSION['user_id'],
                    "pdoType" => PDO::PARAM_INT
                )
            );

            // Create our new file object to handle the upload and get file info.
            $fileHandler = new FileHandler($_FILES['file']);

            // Upload the song
            $fileHandler->songUpload($formArr);

            // Get the source for the html audio player
            $src = $fileHandler->getSrc();

            // Putting the audio player into the ajax response to dynamically show it.
            $msg_string = "Your song has been uploaded!<br/><audio controls><source src=\"" . $src . "\" type=\"audio/mp3\">Your browser does not support the audio element.</audio>";

            $response['uploaded'] = $msg_string;
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;

    } else {
        // Die. Kill the scripe. Redirect the user.
        header("Location: " . Url::getBasePath() . "index.php");
        exit('Invalid URL');
    }
?>
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

        $mime = $_FILES['file']['type'];

        $valid_mp3_mimes = array(
            'audio/mpeg',
            'audio/x-mpeg',
            'audio/mp3',
            'audio/x-mp3',
            'audio/mpeg3',
            'audio/x-mpeg3',
            'audio/x-mpeg-3',
            'audio/mpg',
            'audio/x-mpg',
            'audio/x-mpegaudio',
            'audio/ogg',
            'audio/mp4',
            'audio/vnd.wav'
        );

        if ($err > 0) {
            // If error value is larger than 0, there is an error
            $response['error'] = "There was an error while uploading the file:<br/>";
        } else if (!in_array($mime, $valid_mp3_mimes)) {
            $response['uploaded'] = "Please choose a supported file type. (i.e. mp3, mp4, ogg, wav)";

        } else {

            // Create an array of the form info to pass into songUpload(). Must be associative array with column name as the key (top hierarchy)
            // and pdoVal as first element key of second level, pdoType as second element key of second level
            $formArr = array(
                ":song_title" => array(
                    "pdoVal" => Utils\Filter::String($_POST['song-title']),
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
            $fileHandler = new Files\FileHandler($_FILES['file']);

            // Upload the song
            $fileHandler->songUpload($formArr);

            // Get the source for the html audio player
            $src = $fileHandler->getSrc();

            // Putting the audio player into the ajax response to dynamically show it.
            $msg_string = "<div class=\"row\"><div class=\"col-12\">Your song has been uploaded!</div></div>";

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
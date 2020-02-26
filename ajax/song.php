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

            $con = DB::getConnection();
            // Setup variables to store

            $fileName = $_FILES['file']['name'];
            $mimeType = $_FILES['file']['type'];
            $fileSize = $_FILES['file']['size'];
            

            // Move to perm directory
            $tempName = $_FILES['file']['tmp_name'];
            $newLocation = '../uploads/' . $_FILES['file']['name'];
            
            move_uploaded_file($tempName, $newLocation);

            $songTitle = Filter::String($_POST['song-title']);
            $artist = $_POST['artist'];
            $album = $_POST['album'];
            $owner = (Int) $_SESSION['user_id'];

             // Store the file info in the database.
            try {
                $addFile = $con->prepare("INSERT INTO files(filename, owner, artist, album, song_title, mime_type) VALUES(:filename, :owner, :artist, :album, :song_title, :mime_type)");
                $addFile->bindParam(':filename', $fileName, PDO::PARAM_STR);
                $addFile->bindParam(':artist', $artist, PDO::PARAM_STR);
                $addFile->bindParam(':album', $album, PDO::PARAM_STR);
                $addFile->bindParam(':song_title', $songTitle, PDO::PARAM_STR);
                $addFile->bindParam(':mime_type', $mimeType, PDO::PARAM_STR);
                $addFile->execute();
            } catch (PDOException $e) {
                error_log($e->getMessage());
            }

            
            $filePath = Url::getBasePath() . 'uploads/' . $fileName;

            $msg_string = "Your song has been uploaded!<br/><audio controls>
            <source src=\"$filePath\" type=\"audio/mp3\">
          Your browser does not support the audio element.
          </audio>";
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
<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "../inc/config.php"; 
    
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        // Always return json format
        header('Content-Type: application/json');
        
        $response = [];

        $user_found = User::Find($_SESSION['user_id'], true);

        $email = $user_found['email'];

        if (isset($_FILES['profileImage']) && $_FILES['profileImage'] !== null) {
            $mime = $_FILES['profileImage']['type'];

            $valid_image_mimes = array(
                'image/jpeg',
                'image/gif',
                'image/png',
                'image/webp',
            );

            if ($user_found && (in_array($mime, $valid_image_mimes))) {

                $con = DB::getConnection();
                
                // The user exists. Update the user's profile image
                if (isset($_FILES['profileImage']) && is_array($_FILES['profileImage'])) {
                    $profile_image = $_FILES['profileImage'];
                    $response['image'] = $profile_image['name'];
                    $response['path'] = __PATH__ . 'uploads/';
                } elseif ($user_found['profile_image'] != null) {
                    $profile_image = $user_found['profile_image'];
                    $response['image'] = $profile_image;
                    $response['path'] = __PATH__ . 'uploads/';
                } else {
                    $profile_image = null;
                    $response['image'] = 'inc/img/default-avatar.png';
                    $response['path'] = __PATH__;
                }

                $file = new FileHandler($profile_image);

                move_uploaded_file($profile_image['tmp_name'], $file->filePath);

            } else {

                // We could not update the user's profile image

                $response['error'] = "<div class='alert alert-dismissible alert-warning'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                <h4 class='alert-heading'>Warning!</h4>
                <p class='mb-0'>There was a problem updating your image.<br />Please choose a valid file type. (i.e. jpg, png, gif, webp)</p>
            </div>";

            }
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
<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "../inc/config.php"; 
    
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        // Always return json format
        header('Content-Type: application/json');
        
        $response = [];

        $user_found = User::Find($_SESSION['user_id'], "", true);

        $email = $user_found['email'];
        $userPic = $user_found['profile_image'];
        $path = dirname(__FILE__, 2) . "/uploads/";


        if ($user_found) {
            
            // The user exists. Update the user's info
            if (isset($_FILES['profileImage']) && is_array($_FILES['profileImage'])) {
                $profile_image = $_FILES['profileImage'];
                $response['image'] = $profile_image['name'];
                $response['path'] = __PATH__ . 'uploads/' . FileHandler::getUserDir();

                // We need to create the uploads directory and user's directory if they haven't been created yet.
                if (!is_dir(dirname(__FILE__, 2) . "/uploads/" . FileHandler::getUserDir())) {
                    mkdir(dirname(__FILE__, 2) . "/uploads/" . FileHandler::getUserDir(), 0777, true);
                    chmod('../uploads', 0777);
                    chmod('../uploads/' . substr(FileHandler::getUserDir($_SESSION['user_id']), 0, strlen(FileHandler::getUserDir($_SESSION['user_id'])) - 1), 0777);
                }

                    
                // Update the user's profile pic
                DB::updateProfilePic($profile_image, $email);

                // Delete the stored profile image to avoid mismatched counts between the the files table and user's uploads dir
                FileHandler::deleteFile($path, $userPic);


            } else {
                // if no file has been selected as a profile image, we show the default avatar
                $profile_image = null;
                $response['image'] = 'inc/img/default-avatar.png';
                $response['path'] = __PATH__;
            }

            // Set up our fields, so we can update the user table
            $first_name = $_POST['firstName'];
            $last_name = $_POST['lastName'];
            $newEmail = $_POST['email'];

            // We need to check here is a value was passed in for the password to avoid hashing empty space.
            $password = (isset($_POST['password']) && $_POST['password'] != null) ? $password = password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

            $birthDate = $_POST['birthDate'];
            $bio = $_POST['bio'];

            // Update the user table
            DB::updateUser($first_name, $last_name, $newEmail, $email, $password, $birthDate, $bio);

            $response['success'] = "<div class=\"alert alert-dismissible alert-success\">Your profile has been saved.</div>";

        } else {

             // We could not update the user's info

             $response['error'] = "<div class='alert alert-dismissible alert-warning'>
             <button type='button' class='close' data-dismiss='alert'>&times;</button>
             <h4 class='alert-heading'>Warning!</h4>
             <p class='mb-0'>There was a problem updating your profile.</p>
           </div>";

        }

        // Return the proper information back to JavaScript to redirect us.

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;

    } else {
        // Die. Kill the script. Redirect the user.
        header("Location: " . __PATH__ . "index.php");
        exit('Invalid URL');
    }
?>
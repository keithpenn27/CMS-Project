<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "../inc/config.php"; 
    
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        // Always return json format
        header('Content-Type: application/json');
        
        $response = [];

        $email = Filter::String($_POST['email']);

        $user_found = User::Find($email);

        if ($user_found) {

            $con = DB::getConnection();
            // The user exists. Update the user's info
            $profile_image = $_FILES['profileImage'];
            $first_name = $_POST['firstName'];
            $last_name = $_POST['lastName'];
            $newEmail = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $birthDate = $_POST['birthDate'];
            $bio = $_POST['bio'];

            DB::updateUser($profile_image, $first_name, $last_name, $newEmail, $email, $password, $birthDate, $bio);

            $response['image'] = $profile_image['name'];
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
        // Die. Kill the scripe. Redirect the user.
        header("Location: " . Url::getBasePath() . "index.php");
        exit('Invalid URL');
    }
?>
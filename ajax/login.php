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
        $password = $_POST['password'];

        $user_found = User::Find($email, true);

        if ($user_found) {
            // User exists, try to sign them in
            $user_id = (int) $user_found['user_id'];
            $hash = $user_found['password'];

            if (password_verify($password, $hash)) {
                // User is signed in
                $_SESSION['user_id'] = $user_id;
                $response['redirect'] = "dashboard.php";
            } else {
                // Invalid user email/password combo
                $response['error'] = "Invalid user email/password combo.";
            }

        } else {
            // They need to create an account.
            $response['error'] = "You do not have an account. <a href='register.php'>Create one now?</a>";
           
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;

    } else {
        // Die. Kill the scripe. Redirect the user.
        header("Location: ../index.php");
        exit('Invalid URL');
    }
?>
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

        // Make sure the user does not exist
        $findUser = $con->prepare("SELECT user_id FROM users WHERE email = LOWER(:email) LIMIT 1");
        $findUser->bindParam(':email', $email, PDO::PARAM_STR);
        $findUser->execute();

        if ($findUser->rowCount() == 1) {
            // User exists
            $response['error'] = "An account has already been registered with that email address.<br/>Please log in.";

        } else {
            // User does not exist, add them now.

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $addUser = $con->prepare("INSERT INTO users(email, password) VALUES(LOWER(:email), :password)");
            $addUser->bindParam(':email', $email, PDO::PARAM_STR);
            $addUser->bindParam(':password', $password, PDO::PARAM_STR);
            $addUser->execute();

            $user_id = $con->lastInsertId();

            $_SESSION['user_id'] = (int) $user_id;

            $response['redirect'] = 'dashboard.php?message=welcome';
            $response['is_logged_in'] = true;
        }

        // Make sure the user CAN be added AND is added

        // Return the proper information back to JavaScript to redirect us.

        $response['redirect'] = 'dashboard.php';

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;

    } else {
        // Die. Kill the scripe. Redirect the user.
        exit('Invalid URL');
    }
?>
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

        // Make sure the user does not exist
        $findUser = $con->prepare("SELECT user_id, password FROM users WHERE email = LOWER(:email) LIMIT 1");
        $findUser->bindParam(':email', $email, PDO::PARAM_STR);
        $findUser->execute();

        if ($findUser->rowCount() == 1) {
            // User exists, try to sign them in

            $user = $findUser->fetch(PDO::FETCH_ASSOC);

            $user_id = (int) $user['user_id'];
            $hash = $user['password'];

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
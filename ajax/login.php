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

        $user_found = User::Find("", $email, true);

        if ($user_found) {
            // User exists, try to sign them in
            $user_id = (int) $user_found['uid'];
            $hash = $user_found['password'];

            if (password_verify($password, $hash)) {
                // User is signed in
                $_SESSION['user_id'] = $user_id;
                $user_first_name = (string) $user_found['first_name'];

                $msg_string = sprintf(__PATH__ . "dashboard/?message=Hello, %s. Welcome back to CMS Project!", $user_first_name);
                $response['redirect'] = $msg_string;
            } else {
                // Invalid user email/password combo
                $response['error'] = "<div class='alert alert-dismissible alert-warning'>
                <button type='button' class='close' data-dismiss='alert';>&times;</button>
                <h4 class='alert-heading'>Warning!</h4>
                <p class='mb-0'>Invalid user email/password combo.</p>
              </div>";
            }

        } else {
            // They need to create an account.
            $response['error'] = "<div class='alert alert-dismissible alert-warning'>
            <button type='button' class='close' data-dismiss='alert';>&times;</button>
            <h4 class='alert-heading'>Warning!</h4>
            <p class='mb-0'>You do not have an account. <a href='register.php'>Create one now?</a></p>
          </div>";
           
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;

    } else {
        // Die. Kill the scripe. Redirect the user.
        header("Location: " . __PATH__);
        exit('Invalid URL');
    }
?>
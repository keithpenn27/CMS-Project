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
            // User exists
            $response['error'] = "<div class='alert alert-dismissible alert-warning'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            <h4 class='alert-heading'>Warning!</h4>
            <p class='mb-0'>An account has already been registered with that email address.<br/>Please <a href='login.php'>log in</a>.</p>
          </div>";

        } else {

            $con = DB::getConnection();
            // User does not exist, add them now.
            $first_name = $_POST['firstName'];
            $last_name = $_POST['lastName'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $addUser = $con->prepare("INSERT INTO users(first_name, last_name, email, password) VALUES(:firstname, :lastname, LOWER(:email), :password)");
            $addUser->bindParam(':firstname', $first_name, PDO::PARAM_STR);
            $addUser->bindParam(':lastname', $last_name, PDO::PARAM_STR);
            $addUser->bindParam(':email', $email, PDO::PARAM_STR);
            $addUser->bindParam(':password', $password, PDO::PARAM_STR);
            $addUser->execute();

            $user_id = $con->lastInsertId();

            $_SESSION['user_id'] = (int) $user_id;

            $user_first_name = (string) $_POST['firstName'];

            $msg_string = sprintf("dashboard.php?message=Hello, %s. Welcome to CMS Project!", $user_first_name);
            $response['redirect'] = $msg_string;

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
<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "../inc/config.php"; 
    
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        // Always return json format
        header('Content-Type: application/json');
        
        $response = [];

        // Make sure the user does not exist

        // Make sure the user CAN be added AND is added

        // Return the proper information back to JavaScript to redirect us.

        $response['redirect'] = 'dashboard.php';
        $response['name'] = 'Keith Pennington';

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;

    } else {
        // Die. Kill the scripe. Redirect the user.
        exit('test');
    }
?>
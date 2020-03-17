<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "../inc/config.php"; 
    
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        // Always return json format
        header('Content-Type: application/json');
        
        $response = [];

        $post_title = $_POST['postTitle'];
        $post_content = null;

        $post_author_id = $_SESSION['user_id'];

        if (isset($_POST['postContent']) && $_POST['postContent'] != null) {

            $post_content = $_POST['postContent'];
        }

        $post = new Content\Blog($post_title, $post_content, $post_author_id);
        $post->updatePost($con);

        $pid = $_POST['getVal'];
        $query = "?pid=" . $pid . "&title=" . $post_title;

        $response['success'] = '<div class="alert alert-dismissible alert-success">' . $post_title . ' has been updated.<br /><a href="' . __PATH__ . 'blog/' . $query . '">View Post</a>';

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;

    } else {
        // Die. Kill the scripe. Redirect the user.
        header("Location: " . __PATH__);
        exit('Invalid URL');
    }
?>
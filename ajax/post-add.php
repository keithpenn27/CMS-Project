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

        $pid;

        if (isset($_POST['postContent']) && $_POST['postContent'] != null) {

            $post_content = $_POST['postContent'];
        }

        $post = new Blog($post_title, $post_content, $post_author_id);
        $post->insertPost($con);

        $pView = Blog::getSinglePost($pid = $con->lastInsertId(), $con);


        $query = "?pid=" . $pid . "&title=" . $pView['post_title'];

        $response['success'] = '<div class="alert alert-dismissible alert-success">' . $post_title . 'was created.<br /><a href="' . __PATH__ . 'blog/' . $query . '">View Post</a></div>';

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;

    } else {
        // Die. Kill the scripe. Redirect the user.
        header("Location: " . __PATH__);
        exit('Invalid URL');
    }
?>
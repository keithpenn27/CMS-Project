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

        $chckPost = $con->prepare("SELECT * FROM posts WHERE post_title = :postTitle LIMIT 1");
        $chckPost->bindParam(":postTitle", $post_title, PDO:: PARAM_STR);
        $chckPost->execute();
        $check = $chckPost->fetch(PDO::FETCH_ASSOC);

        if ($check) {
            $response['error'] = '<div class="alert alert-dismissible alert-warning">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4 class="alert-heading">Warning!</h4>A post with this title already exists. Edit the <a href="' . __PATH__ . 'post-edit/?pid=' . $check['pid'] . '&title=' . $check['post_title'] . '">existing post</a> or change the title of the current post.</div>';
        } else {

        $pid;

        if (isset($_POST['postContent']) && $_POST['postContent'] != null) {

            $post_content = $_POST['postContent'];
        }

        $post = new Blog($post_title, $post_content, $post_author_id);
        $post->insertPost($con);

        $pView = Blog::getSinglePost($pid = $con->lastInsertId(), $con);


        $query = "?pid=" . $pid . "&title=" . $pView['post_title'];

        $response['success'] = '<div class="alert alert-dismissible alert-success">' . $post_title . ' was created.<br /><a href="' . __PATH__ . 'blog/' . $query . '">View Post</a><br/><a href="' . __PATH__ . 'post-edit/' . $query . '">Edit Post</a></div>';

    }
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;

    } else {
        // Die. Kill the scripe. Redirect the user.
        header("Location: " . __PATH__);
        exit('Invalid URL');
    }
?>
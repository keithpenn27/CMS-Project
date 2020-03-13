<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "inc/config.php";

    require_once "inc/header.php";

    Page::ForceLogin();

    $user = User::getCurrentUser();
    
    if (isset($_GET['title']) && $_GET['title'] != null && isset($_GET['pid']) && $_GET['pid'] != null) {
        $postTitle = $_GET['title'];
        $pid = $_GET['pid'];
        
        $post = $con->prepare("DELETE FROM posts WHERE pid = :pid");
        $post->bindParam(":pid", $pid, PDO::PARAM_INT);
        $post->execute();
    }
    
?>

    <div class="container">
        <div class="row">
        <div class="alert alert-dismissible alert-warning">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h3><?php echo $postTitle ?> has been deleted.</h3>
            </div>
        </div>
    </div>

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
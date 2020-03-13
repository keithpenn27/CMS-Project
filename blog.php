<?php

    // Allow the config
    define('__CONFIG__', true);

    // Require the config file
    require_once "inc/config.php";

    require_once "inc/header.php";

    if (isset($_GET['pid']) && $_GET['pid'] != null) :
      $pid = $_GET['pid'];

      $postInfo = Blog::getSinglePost($pid , $con);

      $author_id = $postInfo['author'];

      $authorInfo = Blog::getPostAuthor($author_id, $con);

      $dir = FileHandler::getUserDir($author_id);

      $authorName = $authorInfo['first_name'] . " " . $authorInfo['last_name'];
      $authorPic = ($authorInfo['profile_image'] != null) ? __PATH__ . 'uploads/' . $dir . $authorInfo['profile_image'] : __PATH__ . 'inc/img/default-avatar.png';
      $authorEmail = $authorInfo['email'];

?>
    <div class="container">
    <div class="row">
     <div class="col-sm-2">
        <h4>Posted By:</h4>
        <img class="profile-img" src="<?php echo $authorPic ?>" />
  
      <div class="name"><strong>Name: </strong></span><span><?php echo $authorName ?></div>
      <div class="age"><strong>Age: </strong></span><span><?php if ($authorInfo['birthdate'] != null): echo User::getAge($authorInfo['uid']); ?> years old<?php endif; ?></div>
          <a class="btn btn-outline-primary" href="mailto:<?php echo $authorEmail ?>">Contact</a>
     </div>
     <div class="col-sm-7">
        <h1><?php echo $postInfo['post_title'] ?></h1>
        <div class="content">
          <?php echo nl2br($postInfo['post_content']) ?>
        </div>
        <?php  
        
            $editLink = (User::userCanEdit($author_id)) ? '<a href="' . __PATH__ . 'post-edit/?pid=' . $pid . '&title=' . $postInfo['post_title'] . '" />Edit</a>' : '';
            $deleteLink = (User::userCanEdit($author_id)) ? '<a href="' . __PATH__ . 'post-delete/?pid=' . $pid . '&title=' . $postInfo['post_title'] . '" />Delete</a>' : '';
          
            echo $editLink . " " . $deleteLink;
          ?>

     </div>
     <div class="col-sm-3">

     </div>
     </div>
    </div>

    <?php else: ?>

    <div class="container">
      <div class="row">
        <div class="col-sm-9">
           <h1>Blog</h1>
          <div class="content">
            <?php DB::getBlogRoll("", 200) ?>
          </div>
        </div>
        <div class="col-sm-3">

        </div>
      </div>
    </div>
    <?php endif; ?>
    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
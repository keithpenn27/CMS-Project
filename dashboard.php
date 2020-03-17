<?php

    // Allow the config
    define('__CONFIG__', true);

    // Require the config file
    require_once "inc/config.php";

    require_once "inc/header.php";

    Utils\Page::ForceLogin();

    $usr = Users\User::getCurrentUser();
  
    $userPic = ($usr['profile_img'] != null) ? __PATH__ . 'uploads/' . Files\FileHandler::getUserDir($usr['uid']) . $usr['profile_img'] : __PATH__ . 'inc/img/default-avatar.png';

    $getPosts = $con->prepare("SELECT * FROM posts WHERE author = :author_id");
    $getPosts->bindParam(":author_id", $usr['uid'], PDO::PARAM_INT);
    $getPosts->execute();

?>
    <div class="container">
      <?php if (isset($_GET['message']) && $_GET['message'] != null): ?>
        <div class="alert alert-dismissible alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p><?php echo $_GET['message'] ?></p>
        </div>
      <?php endif; ?>
        
      <div class="row">
        <div class="col-sm-2">
          <div class="profile-image">
            <img class="profile-img" src="<?php echo $userPic ?>" />
          </div>
          <div class="name"><strong>Name: </strong></span><span><?php echo $usr['first_name'] . " " . $usr['last_name']; ?></div>
      <div class="age"><strong>Age: </strong></span><span><?php if ($usr['birthdate'] != null): echo Users\User::getAge($_SESSION['user_id']); ?> years old<?php endif; ?></div>
        </div>
        <div class="col-sm-6">
          <h2>About <?php echo $usr['first_name']; ?></h2>
          <div class="content">
            <?php echo nl2br($usr['bio']) ?>
          </div>
        </div>

        <!-- Query db to get list of songs and images -->

        <div class="col-sm-4">
          <h4>My Recent Uploads</h4>
          <div class="sidebar-block">
            <h5>Songs</h5>
            <div class="file-list">
              <?php echo Files\FileHandler::getSongs($usr['uid'], $con) ?>
            </div>
          </div>
          <div class="sidebar-block">
            <h5>Pictures</h3>
            <div class="file-list">
              <?php echo Files\FileHandler::getImages($usr['uid'], $con) ?>
            </div>
          </div>
          <div class="sidebar-block">
            <h5>Blog Posts</h3>
            <div class="file-list">
              <?php System\DB::getBlogRoll($usr['uid'], 70) ?>
            </div>
          </div>
        </div>
      </div>
    </div>

     <!-- TODO Add content to user's dashboard. Perhaps, a news feed. -->

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "inc/config.php";

    Page::ForceLogin();

    $usr = User::getCurrentUser();
  
    $userPic = ($usr['profile_img'] != null) ? Url::getBasePath() . 'uploads/' . $usr['profile_img'] : 'inc/img/default-avatar.png';

    require_once "inc/header.php";
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
          <div class="first-name"><strong>First Name: </strong></span><span><?php echo $usr['first_name']; ?></div>
          <div class="last-name"><strong>Last Name: </strong></span><span><?php echo $usr['last_name']; ?></div>
          <div class="last-name"><strong>Birth Date: </strong></span><span><?php echo $usr['birthdate']; ?></div>
          <a class="btn btn-outline-primary" href="mailto:<?php echo $usr['email'] ?>">Contact</a>
        </div>
        <div class="col-sm-7">
          <h2>About <?php echo $usr['first_name']; ?></h2>
          <div class="content">
            <?php echo nl2br($usr['bio']) ?>
          </div>
        </div>

        <!-- Query db to get list of songs and images -->

        <div class="col-sm-3">
          <h4>My Recent Uploads</h4>
          <div class="sidebar-block">
            <h5>Songs</h5>
          </div>
          <div class="sidebar-block">
            <h5>Pictures</h3>
          </div>
        </div>
      </div>
    </div>

     <!-- TODO Add content to user's dashboard. Perhaps, a news feed. -->

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
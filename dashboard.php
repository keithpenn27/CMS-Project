<?php

    // Allow the config
    define('__CONFIG__', true);

    // Require the config file
    require_once "inc/config.php";

    require_once "inc/header.php";

    Page::ForceLogin();

    $usr = User::getCurrentUser();
  
    $userPic = ($usr['profile_img'] != null) ? __PATH__ . 'uploads/' . $usr['profile_img'] : __PATH__ . 'inc/img/default-avatar.png';

    $getFile = $con->prepare("SELECT * FROM files WHERE owner = :uid AND mime_type LIKE 'audio/%' ORDER BY upload_date DESC LIMIT 5");
    $getFile->bindParam(':uid', $usr['uid']);
    $getFile->execute();

    $sListDisplay = "";

    while ($file = $getFile->fetch(PDO::FETCH_ASSOC)) {
      $sListDisplay .= "<div class=\"song-wrapper\"><div class=\"song-info\"><strong>Song Title:</strong> " . $file['song_title'] . "<br />";
      $sListDisplay .= "<strong>Artist:</strong> " . $file['artist'] . "<br />";
      $sListDisplay .= "<strong>Album:</strong> " . $file['album'] . "<br />";
      $sListDisplay .= "<strong>Uploaded On:</strong> " . $file['upload_date'] . "<br /></div>";
      $sListDisplay .= "<audio controls><source src=\"" . __PATH__ . 'uploads/' . $file['filename'] . "\" type=\"audio/mp3\">Your browser does not support the audio element.</audio>";
    }

    $getFile = $con->prepare("SELECT * FROM files WHERE owner = :uid AND mime_type LIKE 'image/%' ORDER BY upload_date DESC LIMIT 5");
    $getFile->bindParam(':uid', $usr['uid']);
    $getFile->execute();

    $iListDisplay = "";

    while ($file = $getFile->fetch(PDO::FETCH_ASSOC)) {
      $iListDisplay .= "<div class=\"song-wrapper\"><div class=\"song-info\"><strong>Image Title:</strong> " . $file['image_title'] . "<br />";
      $iListDisplay .= "<strong>Uploaded On:</strong> " . $file['upload_date'] . "<br /></div>";
      $iListDisplay .= "<img src=\"" . __PATH__ . 'uploads/' . $file['filename'] . "\" width=\"100%\"/>";
    }


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
      <div class="age"><strong>Age: </strong></span><span><?php if ($usr['birthdate'] != null): echo User::getAge($_SESSION['user_id']); ?> years old<?php endif; ?></div>
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
            <div class="file-list">
              <?php echo $sListDisplay ?>
            </div>
          </div>
          <div class="sidebar-block">
            <h5>Pictures</h3>
            <div class="file-list">
              <?php echo $iListDisplay ?>
            </div>
          </div>
        </div>
      </div>
    </div>

     <!-- TODO Add content to user's dashboard. Perhaps, a news feed. -->

    <?php require_once "inc/footer.php"; ?>

    </body>
</html>
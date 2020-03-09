<?php
    if(!defined('__CONFIG__')) {
      header('Location: ' . __PATH__);
      exit;

  }

  require_once "config.php";

    $user = (isset($_SESSION['user_id']) && $_SESSION['user_id'] != null) ? new User($_SESSION['user_id']) : false;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href=<?php echo __PATH__; ?>>CMS Project</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarColor01">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href=<?php echo __PATH__ ?>>Home</a>
      </li>
      <li class="nav-item">
      <a class="nav-link" href=<?php echo __PATH__ . "blog/" ?>>Blog</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Pricing</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">About</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Search">
      <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
    </form>

    <?php 
      if ($user): 
    ?>

    <ul class="navbar-nav nav-right">
      <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <?php echo $user->email ?>
      </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href=<?php echo __PATH__ . 'dashboard/'; ?>>My Profile</a>
          <a class="dropdown-item" href=<?php echo __PATH__ . 'edit-profile/'; ?>>Edit Profile</a>
          <a class="dropdown-item" href=<?php echo __PATH__ . 'post-add/'; ?>>New Blog Post</a>
          <a class="dropdown-item" href=<?php echo __PATH__ . 'song/'; ?>>Upload Song</a>
          <a class="dropdown-item" href=<?php echo __PATH__ . 'image/'; ?>>Upload Image</a>
          <a class="dropdown-item" href=<?php echo __PATH__ . 'logout/'; ?>>Logout</a>
        </div>
      </li>
    </ul>

    <?php else: ?>
      <a class="nav-link" href=<?php echo __PATH__ . 'register/'; ?>>Sign Up</a>
      <a class="nav-link" href=<?php echo __PATH__ . 'login/'; ?>>Login</a>
    <?php endif; ?>
  </div>
</nav>
<?php
    if(!defined('__CONFIG__')) {
      header('Location: ../index.php');
      exit;

  }
    $user = (isset($_SESSION['user_id']) && $_SESSION['user_id'] != null) ? new User($_SESSION['user_id']) : false;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href=<?php echo Url::getBasePath() . 'index.php' ?>>CMS Project</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarColor01">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href=<?php echo Url::getBasePath() . 'index.php'; ?>>Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Features</a>
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
          <a class="dropdown-item" href=<?php echo Url::getBasePath() . 'dashboard.php'; ?>>My Profile</a>
          <a class="dropdown-item" href=<?php echo Url::getBasePath() . 'logout.php'; ?>>Logout</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
    </ul>

    <?php else: ?>
      <a class="nav-link" href=<?php echo Url::getBasePath() . 'register.php'; ?>>Sign Up</a>
      <a class="nav-link" href=<?php echo Url::getBasePath() . 'login.php'; ?>>Login</a>
    <?php endif; ?>
  </div>
</nav>
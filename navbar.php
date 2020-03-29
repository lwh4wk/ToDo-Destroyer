
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Todo Destroyer</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">

    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?php if ($_SERVER['REQUEST_URI'] == '/todo-destroyer/login.php' || $_SERVER['REQUEST_URI'] == '/login.php') { ?>
                <a class="nav-link" href="signup.php">Sign Up</a>
            <?php } else { ?>
                <a class="nav-link" href="login.php">Sign In</a>
            <?php } ?>
        </li>
    </ul>
  </div>
</nav>
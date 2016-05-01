<?php
function processLogin () {
  if ($_SESSION["user"] != '') {
    $user = $_SESSION['user'];
    echo <<< END
    <p style="font-size:18px; float: right; margin-top: 40px; margin-right: 20px;">
      Welcome <b>$user</b>!
    </p>
END;
  }
  else {
     echo <<< END
     <form class="navbar-form navbar-right" style="margin-top: 35px;" method="POST" action="login.php">
        <input type="text" size="9" name="username" class="form-control" placeholder="Username">
        <input type="password" size="9" name="password" class="form-control" placeholder="Password">
        <button type="submit" name="loginSubmit" class="btn btn-success">Submit</button>
     </form>
END;
  }
}
?>

<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
    <div class="col-md-12" style="background-color: tan; border-bottom: 2px solid black; box-shadow: 3px 3px 5px #888888;">
    <a href="http://www.cis355.com/student14/landing.php"><img src="http://www.cis355.com/student14/LWIP_logo.png" style="margin-top: 5px;"></a>
    <?php processLogin(); ?>
    <br>
    <br>
    </div>
</nav>

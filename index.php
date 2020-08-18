<?php
include('includes/init.php');
$current_page = 'index.php';


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/main.css"/>
  <title>Home</title>
</head>

  <?php
  include('includes/header.php');

  ?>
  <h2> Welcome to the Website </h2>
  <p id = 'intro'> On this page you will be able to view images in the image gallery and
    sort them by tags. Everyone is welcome to add tags to images they find suitable.
    Only logged in users can upload images and only uploaders can edit or delete individual pictures </p>
  <hr id = 'introhr'>
  <?php
  if ($current_user){
    echo "<h3>Logged In As: " . $current_user . "</h3>";
  } else {
  ?>
  <div id = 'login'>
    <h3> Login </h3>
    <form name = 'login' method = "POST" >
      <p> Username </p>
      <input name = 'username' type = 'text' required>
      <p> Password </p>
      <input name = 'password' type = 'password' required>
      <button class = 'login' name = 'login_submit' type = 'submit' value = 'login' > Login </button>
    </form>

    <?php
    foreach ($login as $login_message){
      echo '<li>' . $login_message . '</li>';
    }
    foreach ($messages as $message){
      echo '<li>' . $message . "</li>";
    }
    ?>
  </div>


  <?php
  }
  include('includes/footer.php');
  ?>
</body>
</html>

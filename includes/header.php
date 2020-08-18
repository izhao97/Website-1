<body>
<header>
  <h1> WALLPAPERS </h1>
  <hr>
</header>
  <div class = 'sidenav'>
    <p id = 'current_user'>
      <?php
      if ($current_user){
        echo "Logged in as: " . $current_user;
      ?>
      </p>
      <form class = 'logout' name = "logout" method = "POST">
        <button type = 'submit' value = 'logout' name = 'logout'> Logout </button>
      </form>
      <?php
      } else {
        echo "<p>Nobody logged in</p>";
      }
     ?>
      <?php
      foreach ($pages as $page_name => $page_id){
        if ($page_id == $current_page){
          $css_id = ' id = current_page ';
        } else {
          $css_id = ' ';
        }
      echo '<a'. $css_id . " href=". $page_id .'>'. $page_name. '</a>';
      }
      ?>
  </div>
  <div class = 'main'>

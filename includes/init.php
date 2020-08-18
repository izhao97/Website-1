<?php


$pages = array(
  "Login" => 'index.php',
  "Image" => 'image.php',
  "Tag" => 'tag.php',
  "Citations" => 'citations.php',
);

CONST UPLOAD_PATH="uploads/";

// show database errors during development.
function handle_db_error($exception) {
  echo '<p><strong>' . htmlspecialchars('Exception : ' . $exception->getMessage()) . '</strong></p>';
}

// execute an SQL query and return the results.
function exec_sql_query($db, $sql, $params = array()) {
  try {
    $query = $db->prepare($sql);
    if ($query and $query->execute($params)) {
      return $query;
    }
  } catch (PDOException $exception) {
    handle_db_error($exception);
  }
  return NULL;
}

$messages = array();
$login = array();
$imagemessages = array();

// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename) {
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_init_sql = file_get_contents($init_sql_filename);
    if ($db_init_sql) {
      try {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
      } catch (PDOException $exception) {
        handle_db_error($exception);
      }
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return NULL;
}

$db = open_or_init_sqlite_db('website.sqlite', "init/init.sql");

function log_in($username, $password) {
  global $db;
  global $messages;
  global $login;
  $sql = "SELECT * FROM accounts WHERE username = :username;";
  $params = array(
    ":username" => $username,
  );
  $records = exec_sql_query($db, $sql, $params)->fetchAll();
  if ($records) {
    $account = $records[0];
    if (password_verify($password, $account['password'])) {
      $session = uniqid();
      $session_sql = "UPDATE accounts SET session = :session WHERE id = :user_id";
      $session_params = array(
        ':user_id' => $account['id'],
        ':session' => $session,
      );
      $result = exec_sql_query($db, $session_sql, $session_params);
      if ($result){
        setcookie("session", $session, time()+3600);
        array_push($login,"Logged in as $username");
        return $username;
      } else {
        array_push($login, "Login Failed, Please Try Again");
      }
    } else {
      array_push($login,"Invalid username or password.");
    }
  } else {
    array_push($login,"Invalid username or password.");
  }
  return NULL;
};

function check_login (){
  global $db;
  if (isset ($_COOKIE['session'])){
    $check_session = $_COOKIE['session'];
    $check_sql = "SELECT * FROM accounts WHERE session = :session";
    $check_params = array(
      ':session' => $check_session,
    );
    $check = exec_sql_query($db, $check_sql,$check_params)->fetchAll();
    if ($check){
      $account = $check[0];
      return $account['username'];
    }
  }
  return NULL;
}

function logout(){
  global $current_user;
  global $db;
  if ($current_user){
    $logout_sql = "UPDATE accounts SET session = :session WHERE username = :username;";
    $logout_params = array(
      ':username' => $current_user,
      ':session' => NULL,
    );
    if (!exec_sql_query($db, $logout_sql, $logout_params)){
      array_push($login = 'Log Out Failed');
    }
  }
  setcookie('session', "", time()-3600);
  $current_user = NULL;
}


if (isset($_POST['login_submit'])) {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $username = trim($username);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $current_user = log_in($username, $password);
} else{
  $current_user = check_login();
};

if(isset($_POST['logout'])){
  logout();
}



?>

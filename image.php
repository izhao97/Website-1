<?php
include('includes/init.php');
$current_page = 'image.php';

$tag_messages = array();
$delete_messages = array();


if (isset($_POST['delete_tag'])){
  $delete_tag_image_id = $_POST['delete_tag_image_id'];
  $get_tag_name_sql = "SELECT id FROM tags WHERE tags_name = :tags_name";
  $get_tag_name_param = array(
    ':tags_name' => ($_POST['delete_tag_name']),
  );
  $get_delete_tag_id_array = exec_sql_query ($db, $get_tag_name_sql, $get_tag_name_param)->fetchAll();
  $delete_tag_id_array = $get_delete_tag_id_array[0];
  $delete_tag_id = $delete_tag_id_array['id'];
  $delete_tag_sql = "DELETE FROM imagetag WHERE image_id = :image_id AND tag_id = :tag_id";
  $delete_tag_params = array(
    ':image_id' => $delete_tag_image_id,
    ':tag_id' => $delete_tag_id,
  );
  if(exec_sql_query($db, $delete_tag_sql, $delete_tag_params)){
      array_push($delete_messages, "Tag Deleted");
  } else {
    array_push($delete_messages, "Tag Deletion Failed, Please Try Again");
  }
}

if (isset($_POST['add_tag'])){
  $temp_id = $_GET['id'];
  $current_tags_image = getTags($temp_id);
  $new_tag = filter_tag_input('tagName');
  $tag_names = get_All_Tags_name();
  $current_tag_names = array();
  foreach ($current_tags_image as $current_tag){
    array_push($current_tag_names, $current_tag);
  };
  if (in_array($new_tag, $current_tag_names)){
    array_push($tag_messages, " Already a tag for the image");
  }elseif (in_array($new_tag, $tag_names)){
    $current_tag_id = get_tag_id($new_tag);
    $add_tag_sql = "INSERT INTO imagetag (image_id, tag_id) VALUES (:image_id, :tag_id)";
    $add_tag_params = array(
      'image_id' => $temp_id,
      'tag_id' => $current_tag_id,
    );
    exec_sql_query($db, $add_tag_sql, $add_tag_params);
    array_push($tag_messages,"Tag Added Successfully");
  } else {
    $new_tag_sql = "INSERT INTO tags(tags_name) VALUES (:tags_name)";
    $new_tag_params = array(
      ':tags_name' => $new_tag,
    );
    exec_sql_query($db, $new_tag_sql, $new_tag_params);
    $new_tag_image_sql = "INSERT INTO imagetag(image_id, tag_id) VALUES (:image_id, :tag_id)";
    $new_tag_image_params = array(
      'image_id' => $_GET['id'],
      'tag_id' => get_tag_id($new_tag),
    );
    exec_sql_query($db, $new_tag_image_sql, $new_tag_image_params);
    array_push($tag_messages, "Tag Added");
  }
}

function num_image_id(){
  global $db;
  $num_image_sql = "SELECT id FROM images";
  $image_ids = array();
  $num_image_params = array();
  $num_images = exec_sql_query($db, $num_image_sql, $num_image_params)->fetchAll();
  foreach($num_images as $num_image ){
    array_push($image_ids, $num_image['id']);
  }
  return $image_ids;
}

if (isset($_GET['id'])){
  $tempid = $_GET['id'];
  $current_tags = getTags($tempid);
}

function getTags($id){
  global $db;
  $tags = array();
  $tags_sql = "SELECT tags.tags_name FROM tags INNER JOIN imagetag ON tags.id = imagetag.tag_id WHERE imagetag.image_id = :image_id;";
  $tags_params = array(
    ':image_id' => $id,
  );
  $tags_execute = exec_sql_query($db, $tags_sql, $tags_params)->fetchAll();
  foreach ($tags_execute as $tag_execute){
    array_push($tags, $tag_execute['tags_name']);
  }
  return $tags;
}

if(isset($_POST['upload'])){
  if(isset($_COOKIE['session'])){
    $upload_info = $_FILES['imageUpload'];
    if ($upload_info['error']== UPLOAD_ERR_OK){
      echo "hi";
      array_push($imagemessages, "Your file has uploaded");
      $pathinfo = pathinfo($upload_info['name']);
      $ext = $pathinfo['extension'];
      $basename = strtolower($pathinfo['filename']);
      $sql = "INSERT INTO images (images_name, images_ext, uploader_name) VALUES (:images_name, :images_ext, :uploader)";
      $params = array(
        ':images_name' => $basename,
        ':images_ext' => $ext,
        ':uploader' => $current_user,
      );
      $result = exec_sql_query($db, $sql, $params);

      $nextid = $db->lastInsertId('id');
      $new_id = UPLOAD_PATH . $nextid . "." . $ext;
      if (move_uploaded_file($upload_info['tmp_name'], $new_id)) {
          array_push($imagemessages, "Your file was successfully added");
      } else{
        array_push($imagemessages, "Move failed, please try again");
      }
    }
  } else {
    array_push($imagemessages, "Please Log In To Upload Image");
  }
}

function filter_tag_input($input){
  $tempinput = filter_input(INPUT_POST, $input, FILTER_SANITIZE_STRING);
  $new_input = trim($tempinput);
  $format_input = ucfirst(strtolower($new_input));
  return $format_input;
}

function get_All_Tags_name(){
  global $db;
  $current_tags_all = array();
  $sql_tag_all = "SELECT tags_name FROM tags";
  $params_tag_all = array();
  $tags_all = exec_sql_query($db, $sql_tag_all, $params_tag_all)->fetchAll();
  foreach ($tags_all as $tag_all){
    array_push($current_tags_all, $tag_all['tags_name']);
  };
  return ($current_tags_all);
}

function get_tag_id($tag){
  global $db;
  $sql_specific_tag = "SELECT id FROM tags WHERE tags_name = :tag";
  $param_specific_tag = array(
    ':tag' => $tag,
  );
  $tag_info_array = exec_sql_query($db, $sql_specific_tag, $param_specific_tag)->fetchAll();
  $tag_info = $tag_info_array[0];
  return $tag_info['id'];

}

function get_all_tags(){
  global $db;
  $get_all_tags_sql = "SELECT * FROM tags";
  $get_all_tags_params = array();
  $all_tag_info = exec_sql_query($db, $get_all_tags_sql, $get_all_tags_params)->fetchAll();
  return $all_tag_info;
}

if (isset($_POST['delete'])){
  $delete_id= $_POST['delete_image_id'];
  $delete_img_ext = $_POST['delete_image_ext'];
  $delete_sql = "DELETE FROM images WHERE id = :delete_id";
  $delete_params = array(
    ':delete_id'=> $delete_id,
  );
  exec_sql_query($db, $delete_sql, $delete_params);
  $delete_image_tag_sql = "DELETE FROM imagetag WHERE image_id = :delete_id";
  $delete_image_tag_params = array(
    ':delete_id' => $delete_id,
  );
  exec_sql_query($db, $delete_image_tag_sql, $delete_image_tag_params);
  $unlink_path = UPLOAD_PATH . $delete_id . "." . $delete_img_ext;
  unlink($unlink_path);
  array_push($delete_messages, 'Your image has been successfully deleted');

}

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

  <?php
  foreach($delete_messages as $delete_messages){
    echo $delete_messages;
  }
  if (isset($_GET['id'])){
    $all_image_ids = num_image_id();
    if (!in_array($tempid, $all_image_ids)){
      echo "<h3> No image with this ID. Please return to images page for a list of all our images. </h3>";
    }else{
      $searchsql = "SELECT * FROM images WHERE id LIKE '%' || :imageid || '%'";
      $searchparams = array(':imageid' => $tempid);
      $imageresult = exec_sql_query($db, $searchsql, $searchparams)->fetchAll();
      $current_image = $imageresult[0];
      echo " <h3>" . strtolower($current_image['images_name']) . "</h3>
        <img src=" . UPLOAD_PATH . $current_image['id'] . "." . $current_image['images_ext'] ." alt= ' ". $current_image['images_name']." '/>
        <p id = 'uploader'> Uploaded By: " . strtolower($current_image['uploader_name']) . "</p>
        <a class = image_citation href = 'citations.php'> Image Citations - Image Id = ". $current_image['id'].  " </a>
        ";
      if (isset($current_user) && ($current_user == $current_image['uploader_name'])){
          echo "<form name = 'delete_image' method = 'POST' action= 'image.php'>
            <input type = 'hidden' name = 'delete_image_id' value = ". $tempid .">
            <input type = 'hidden' name = 'delete_image_ext' value = " . $current_image['images_ext'] .">
            <button name = 'delete' type = 'submit'>Delete Image </button>
            </form> ";
      }
      echo "<h3>  Tags </h3>
        <ul class = 'tags'>";
        foreach ($current_tags as $current_tag){
          echo '<li>' . $current_tag . '</li>';
        }
        echo "</ul> ";
        if (isset($current_user) && ($current_user == $current_image['uploader_name'])&& ($current_tags != NULL)){
              echo "<h3> Delete Tag From Image </h3>
                <form name = 'delete_tag' method = 'POST' action='image.php?id=" . $tempid . "'>
                <select name = 'delete_tag_name'>";
                foreach ($current_tags as $current_tag){
                  echo "<option value ='" . $current_tag . "'>" . $current_tag . "</option>";
                }
          echo"</select>
              <input type = 'hidden' name = 'delete_tag_image_id' value =" . $tempid . ">
              <button name = 'delete_tag' type = 'submit'> Delete Tag </button>
              </form>";

        }elseif (isset($current_user) && ($current_user == $current_image['uploader_name'])){
          echo "<h3> Delete Tag From Image </h3>
              <p id = 'delete'>No Tags For Current Image</p>";
        }

?>
  <h3> Add Tag to Image </h3>
  <?php
    echo "<form name = 'tag_upload' method = 'POST' action='image.php?id=" . $tempid . "'>";
  ?>
    <input name="tagName" type = 'text' required />
    <button name = "add_tag" type = 'submit'> Add Tag </button>
  </form>

<?php

    foreach($tag_messages as $tag_message){
      echo $tag_message;
    };

  }
} else {
  ?>
  <div class = 'section'>
    <h2> Images </h2>

    <form name = "image_upload" method="POST" enctype="multipart/form-data" action="image.php">
      <p> Upload File: </p>
      <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
      <input name="imageUpload" id="imageUpload" type="file" required/>
      <input name='upload' type='submit' value='Submit' />
    </form>


    <?php
    foreach ($imagemessages as $immagemessage){
      echo "<h4> $immagemessage </h4>";
    }
    $sqlimages = "SELECT * FROM images";
    $paramimages = array();
    $all_images = exec_sql_query($db, $sqlimages, $paramimages);
    foreach ($all_images as $image){
      $srcpath = UPLOAD_PATH . $image['id'] .  "." . $image['images_ext'];
      echo "<a href = 'image.php?id=". $image['id'] ."'> <img src = $srcpath alt = '" . $image['images_name']. "'/></a>";
    }
  ?>
  <h2><a href = 'citations.php'> All Image Citations </a></h2>
  </div>
  <?php
  }

  ?>
  <?php
  include('includes/footer.php');
  ?>
</body>
</html>

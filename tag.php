<?php
include('includes/init.php');
$current_page = 'tag.php';

if (isset($_GET['id'])){
  $temptag = $_GET['id'];
}

function num_tag_id(){
  global $db;
    $tags_ids = array();
  $num_tag_sql = "SELECT id FROM tags";
  $num_tag_params = array();
  $num_tags = exec_sql_query($db, $num_tag_sql, $num_tag_params)->fetchAll();
  foreach($num_tags as $num_tag ){
    array_push($tags_ids, $num_tag['id']);
  }
  return $tags_ids;
}


function getPicturetag($id){
    global $db;
    $pictures_sql = "SELECT images.id, images.images_name, images.images_ext FROM images
                    INNER JOIN imagetag ON images.id = imagetag.image_id WHERE imagetag.tag_id = :tag_id";
    $pictures_params = array(
      ':tag_id' => $id,
    );
    $current_pictures = exec_sql_query($db, $pictures_sql, $pictures_params)->fetchAll();
    return $current_pictures;



}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/main.css"/>
  <title>Tag</title>
</head>

  <?php
  include('includes/header.php');
  ?>
  <div class = 'section'>
    <h2> Tags </h2>
  </div>


  <?php
  if (isset($_GET['id'])){
    $all_tag_ids = num_tag_id();
    if (!in_array($temptag,$all_tag_ids)){
      echo "<h3> No current tag with this ID. Please return to tags page for a list of all our tags </h3>";
    }else{
      $search_tag_sql = "SELECT * FROM tags WHERE id LIKE '%' || :tag_id || '%'";
      $search_tag_param = array(
        ':tag_id' => $temptag,
      );
      $search_tag_result = exec_sql_query($db, $search_tag_sql, $search_tag_param)->fetchAll();
      $current_tag = $search_tag_result[0];
      echo "<h5>" . $current_tag['tags_name'] . "</h5>";
      $pictures = getPicturetag($temptag);
      foreach ($pictures as $picture){
        $pic_srcpath = UPLOAD_PATH . $picture['id'] . "." . $picture['images_ext'];
        echo "<a href = 'image.php?id=" . $picture['id'] . "'><img src = $pic_srcpath alt ='" . $picture['images_name'] . "'/></a>";
      }
   }
  } else{
    $sql_tag = "SELECT * FROM tags";
    $params_tag = array();
    $all_tags = exec_sql_query($db, $sql_tag, $params_tag)->fetchAll();
    foreach ($all_tags as $temptag){
      echo "<a href = 'tag.php?id=". $temptag['id']."'> <h5>" . $temptag ['tags_name']. "</h5></a>";
    }
  }



   ?>
  <?php
  include('includes/footer.php');
  ?>
</body>
</html>

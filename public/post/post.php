<?php
session_start();

require_once("../library.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $_SESSION["post_title"] = h_s($_POST["post_title"]);

  $_SESSION["post_text"] = h_s($_POST["post_text"]);

  $img_filename = null;

  if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

    if (!preg_match("/^image\//", $_FILES["image"]["type"])) {
      header("HTTP/1.1 302 Found");
      header("Location: form.php");
      exit;

    }

    $path_info = pathinfo($_FILES["image"]["name"]);
    $extension = $path_info["extension"];

    $img_filename = strval(time()) . bin2hex(random_bytes(25)) . "." . $extension;
    $file_path = __DIR__ . "/../upload/image/" . $img_filename;

    move_uploaded_file($_FILES["image"]["tmp_name"], $file_path);


    $_SESSION["post_imgfilename"] = $img_filename;

  }


} else {
  header("Location: ../index.php");
  exit;

}



header("Location: pre.php");
exit;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web掲示板</title>
</head>

<?php
session_start();

require_once("../library.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $_SESSION["post_title"] = h_s($_POST["post_title"]);

  $_SESSION["post_text"] = h_s($_POST["post_text"]);


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

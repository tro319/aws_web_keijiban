<?php
session_start();

require_once("../library.php");

// 投稿結果変数初期化

$_SESSION["post_result"] = "投稿に失敗しました";


$pdo = db();

// ログインしているユーザーIDを取得

if (!empty($_SESSION["log_name"])) {


  $log_name = h_s($_SESSION["log_name"]);


  $sql_id = "SELECT user_id FROM users WHERE name = :name";

  $stmt = $pdo->prepare($sql_id);

  $stmt->bindParam(":name", $log_name, PDO::PARAM_STR);

  $stmt->execute();

  $result = $stmt->fetchAll();

  foreach ($result as $rs) {

    $log_id = $rs["user_id"];

  }

  if (!empty($_POST["post_title"])) {

    $post_title = h_s($_POST["post_title"]);

    $post_text = h_s($_POST["post_text"]);

    if (!empty($_SESSION["post_imgfilename"])) {

    $sql = "INSERT INTO posts2 VALUES(null, :text, :img, :log_id, :title, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":text", $post_text, PDO::PARAM_STR);

    
    $stmt->bindParam(":img", $_SESSION["post_imgfilename"], PDO::PARAM_STR);




    $stmt->bindParam(":log_id", $log_id, PDO::PARAM_INT);

    $stmt->bindParam(":title", $post_title, PDO::PARAM_STR);

    $stmt->execute();

    
    $_SESSION["post_result"] = "投稿に成功しました!";


    } else {

      $sql2 = "INSERT INTO posts2 VALUES(null, :text, null, :log_id, :title, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";

      $stmt2 = $pdo->prepare($sql2);

      $stmt2->bindParam(":text", $post_text, PDO::PARAM_STR);

      $stmt2->bindParam(":log_id", $log_id, PDO::PARAM_INT);

      $stmt2->bindParam(":title", $post_title, PDO::PARAM_STR);

      $stmt2->execute();

      $_SESSION["post_result"] = "投稿に成功しました!";


  }
  header("HTTP/1.1 302 Found");
  header("Location: finish.php");
  exit;

} else {
  header("HTTP/1.1 302 Found");
  header("Location: ../index.php");
  exit;

}

}

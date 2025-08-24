<?php

  require_once("library.php");

  // 指定されたuser_idから、usernameを取得する
  
  function get_username($user_id) {

    $pdo = db();

    $sql = "SELECT name FROM users WHERE user_id = :user_id";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetchAll();
   
    foreach ($result as $rs) {


      $user_name = h_s($rs["name"]);

    }

    return $user_name;

  }



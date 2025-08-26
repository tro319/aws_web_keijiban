<?php

require_once("../library.php");

function get_myposts($user_name) {

  $pdo = db();

  $sql = "SELECT * FROM posts2 JOIN users ON posts2.user_id = users.user_id WHERE users.name = :name";

  $stmt = $pdo->prepare($sql);

  $stmt->bindParam(":name", $user_name, PDO::PARAM_STR);

  $stmt->execute();

  $results = $stmt->fetchAll();


  return $results;

}



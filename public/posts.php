<?php 


require_once("library.php");


function get_posts() {

  $pdo = db();

  $sql = "SELECT * FROM posts2";

  $stmt = $pdo->prepare($sql);

  $stmt->execute();

  $results = $stmt->fetchAll();


  return $results;


}

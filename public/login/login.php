<?php
session_start();

require("../library.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

// セッションへの、データ登録処理

  $_SESSION["log_email"] = h_s($_POST["log_email"]);
  $_SESSION["log_pass"] = h_s($_POST["log_pass"]);


  $email = $_SESSION["log_email"];
  $pass = $_SESSION["log_pass"];


// ログイン認証
  
  $pdo = db();

  $sql_e = "SELECT * FROM users WHERE email = :email";

  $stmt_e = $pdo->prepare($sql_e);

  $stmt_e->bindParam(":email", $email, PDO::PARAM_STR);

  $stmt_e->execute();

  $results_e = $stmt_e->fetchAll();


// 一致するメールアドレスがあれば

  if (!empty($results_e)) {

  
  
  }




}




<?php
session_start();

require("../library.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

// セッションへの、データ登録処理

  $_SESSION["log_email"] = h_s($_POST["log_email"]);


  $email = $_SESSION["log_email"];
  $pass = h_s($_POST["log_pass"]);


// ログイン認証
  
  $pdo = db();

  $sql_e = "SELECT * FROM users WHERE email = :email";

  $stmt_e = $pdo->prepare($sql_e);

  $stmt_e->bindParam(":email", $email, PDO::PARAM_STR);

  $stmt_e->execute();

  $results_e = $stmt_e->fetchAll();


// 一致するメールアドレスがあれば

  $cnt_p = 0;

  if (!empty($results_e)) {


    foreach($results_e as $result) {

      if (password_verify($pass, $result["pass"])) {

        $cnt_p += 1;

        $_SESSION["log_name"] = h_s($result["name"]);

      }

    }
  
  } else {

    $_SESSION["login_error"] = "メールアドレスが正しくありません";

  }


  if ($cnt_p > 0) {

    $_SESSION["login_result"] = "ようこそ、" . $_SESSION["log_name"] . "さん!";

  
  } elseif (!empty($results_e)) {

    $_SESSION["login_error"] = "パスワードが正しくありません";

  }

  if ($cnt_p > 0) {
    header("HTTP/1.1 302 Found");
    header("Location: ../index.php");
    exit;
  } else {
    header("HTTP/1.1 302 Found");
    header("Location: form.php");
    exit;

  }

}




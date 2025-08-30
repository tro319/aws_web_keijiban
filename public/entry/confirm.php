<?php
session_start();

require("../library.php");


// セッションへのデータ登録処理


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $_SESSION["entry_name"] = h_s($_POST["entry_name"]);
  $_SESSION["entry_email"] = h_s($_POST["entry_email"]);
  $_SESSION["entry_pass"] = h_s($_POST["entry_pass"]);



// DB接続

  $pdo = db();

// DB既存データへの重複確認(name)


  $sql_n = "SELECT COUNT(name) AS count FROM users WHERE name = :name";

  $user_name = h_s($_POST["entry_name"]);

  $stmt_n = $pdo->prepare($sql_n);


  $stmt_n->bindParam(":name", $user_name, PDO::PARAM_STR);

  $stmt_n->execute();

  $n_count = $stmt_n->fetch(PDO::FETCH_ASSOC);



  // DB既存データへの重複確認(email)


  $sql_e = "SELECT COUNT(email) AS count FROM users WHERE email = :email";

  $email = h_s($_POST["entry_email"]);

  $stmt_e = $pdo->prepare($sql_e);


  $stmt_e->bindParam(":email", $email, PDO::PARAM_STR);

  $stmt_e->execute();

  $e_count = $stmt_e->fetch(PDO::FETCH_ASSOC);


  if ($e_count["count"] < 1 && $n_count["count"] < 1) {
    header("HTTP/1.1 302 Found");
    header("Location: pre.php");
    exit;

  } elseif ($n_count["count"] > 0) {

    $_SESSION["user_error"] = "入力されたユーザーネームは、既に登録されています。";

  } else {

    $_SESSION["user_error"] = "入力されたメールアドレスは、既に登録されています。";

  }
  header("HTTP/1.1 302 Found");
  header("Location: form.php");
  exit;


} else {
  header("HTTP/1.1 302 Found");
  header("Location: ../index.php");
  exit;

}

<?php
session_start();

require("../library.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $user_name = h_s($_POST["entry_name"]);

  $email = h_s($_POST["entry_email"]);

  $pass = h_s($_POST["entry_pass"]);

	$pdo = db();

	$sql = "INSERT INTO users VALUES(null, :name, :email, :pass)";

	$stmt = $pdo->prepare($sql);

	$stmt->bindParam(":name", $user_name, PDO::PARAM_STR);

	$stmt->bindParam(":email", $email, PDO::PARAM_STR);

	$stmt->bindParam(":pass", $pass, PDO::PARAM_STR);

	$stmt->execute();

  $_SESSION["entry_result"] = "会員登録に成功しました";

} else {
  header("HTTP/1.1 302 Found");
  header("Location: ../index.php");
  exit;

}

if ($_SESSION["entry_result"]) {
  header("HTTP/1.1 302 Found");
	header("Location: finish.php");
	exit;
}





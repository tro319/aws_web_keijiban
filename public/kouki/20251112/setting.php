<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


// セッションの開始
session_start();


// ユーザーがログインしているか、チェック

if (empty($_SESSION["login_id"])) {

  header("HTTP/1.1 302 Found");
  header("Location: login.php");

}


// DBに接続
$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

// 画像があれば、取得

// ログイン中ユーザーid取得

$userID = $_SESSION["user_log_id"];

$sql = "SELECT * FROM users WHERE id = :id";

$stmt = $dbh->prepare($sql);

$stmt->bindParam(":id", $userID, PDO::PARAM_INT);

$stmt->execute();

$resultUser = $stmt->fetchAll();

print_r($resultUser);

?>

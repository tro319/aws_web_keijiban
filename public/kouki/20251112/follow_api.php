<?php
session_start();

$loginId = $_SESSION["login_id"] ?? "";

if (!loginId) exit("NG");

$targetId = intval($_POST["target_id"] ?? 0);

if (!$targetId) exit("NG");


$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

$sql = "INSERT IGNORE INTO followings (follow_id, follower_id) VALUES (?, ?)";

$stmt = $dbh->prepare($sql);

$stmt->execute([$targetId, $loginId]);

echo "OK";



<?php
session_start();

$loginId = $_SESSION["login_id"] ?? "";

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (empty($_GET["id"])) {

    echo "ユーザーが指定されていません";
    exit;

}

$userId = intval($_GET["id"]);

$sql = "SELECT * FROM users WHERE id = :id";

$stmt = $dbh->prepare($sql);

$stmt->execute([":id" => $userId]);

$userResult = $stmt->fetch();


if (!$userResult) {

    echo "ユーザーが存在しません";
    exit;

}

?>

<h2 class="page_title">ユーザー情報</h2>

<div class="article user_info" style="padding: 25px 40px">

    <?php if (!empty($userResult["img_name"])): ?>

        <img src="/upload/image/<?= htmlspecialchars($userResult["img_name"]) ?>" width="80" height="65" />

    <?php endif; ?>

    <p>ユーザーネーム: <?= htmlspecialchars($userResult["name"]) ?></p>
    <p>自己紹介: <?= nl2br(htmlspecialchars($userResult["introd"])) ?></p>

    <div class="back_link">

        <p><a href="board.php">◀ 掲示板に戻る</a></p>

    </div>

</div>






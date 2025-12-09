<?php
session_start();

$loginID = $_SESSION["login_id"] ?? "";

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

// GETパラメータチェック
if (empty($_GET["id"])) {
    echo "ユーザーが指定されていません。";
    exit;
}

$userID = intval($_GET["id"]);

// ユーザー情報取得
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $dbh->prepare($sql);
$stmt->execute([":id" => $userID]);
$user = $stmt->fetch();

if (!$user) {
    echo "ユーザーが存在しません。";
    exit;
}
?>

<h1>会員情報</h1>

<div style="border:1px solid #ccc; padding:1em; width:300px;">

    <?php if (!empty($user["img_name"])): ?>
        <img src="/upload/image/<?= htmlspecialchars($user["img_name"]) ?>"
            style="height: 5em; width: 5em; border-radius: 50%; object-fit: cover;">
    <?php else: ?>
        <div style="height:5em;width:5em;border-radius:50%;background:#ddd;"></div>
    <?php endif; ?>

    <p>ユーザーネーム: <?= htmlspecialchars($user["name"]) ?></p>
    <p>メールアドレス: <?= htmlspecialchars($user["email"]) ?></p>
    <p>自己紹介: <?= nl2br(htmlspecialchars($user["introd"])) ?></p>

</div>

<p><a href="board.php">▶ 掲示板に戻る</a></p>

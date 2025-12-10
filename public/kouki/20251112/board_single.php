<?php
session_start();

$loginId = $_SESSION["login_id"] ?? "";

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (empty($_GET["id"])) {

    echo "投稿が指定されていません";
    exit;

}

$postId = intval($_GET["id"]);

$sql = "SELECT * FROM board_posts JOIN users ON board_posts.user_id = users.id WHERE board_posts.id = :id";

$stmt = $dbh->prepare($sql);

$stmt->execute([":id" => $postId]);

$postResult = $stmt->fetch();





if (!$postResult) {

    echo "投稿が見つかりません";
    exit;

}

?>

<!-- 投稿情報 一件分 -->


<h2 class="page_title">投稿情報</h2>

<div class="article post_info" style="padding: 25px 40px;">

   <?php if (!empty($postResult)): ?>


    <p>投稿内容: <?= nl2br(htmlspecialchars($postResult["content"])) ?></p>

    <div class="user_link">

      <a href="user.php?id=<?= $postResult["user_id"] ?>">

        <img src="/upload/image/<?= htmlspecialchars($postResult["img_name"]) ?>" width="80" height="60" />

        <strong style="text-dceration: none; color: #000;"><?= htmlspecialchars($postResult["name"]) ?></strong>

      </a>

  <?php endif; ?>


</div>




<div class="back_link">

  <p><a href="board.php">◀ 掲示板に戻る</a></p>

</div>


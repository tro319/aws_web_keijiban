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



$sql = "SELECT * FROM board_posts JOIN users ON board_posts.user_id = users.id WHERE user_id = :id";

$stmt = $dbh->prepare($sql);

$stmt->execute([":id" => $userId]);

$userPosts = $stmt->fetchAll();


if (!$userResult) {

    echo "ユーザーが存在しません";
    exit;

}

?>

<!-- ユーザー情報 -->


<h2 class="page_title">ユーザー情報</h2>

<div class="article user_info" style="padding: 25px 40px;">

    <?php if (!empty($userResult["img_name"])): ?>

        <img src="/upload/image/<?= htmlspecialchars($userResult["img_name"]) ?>" width="80" height="65" />

    <?php endif; ?>

    <p>ユーザーネーム: <?= htmlspecialchars($userResult["name"]) ?></p>

    <?php if (!empty($userResult["introd"])): ?>
    
        <p>自己紹介: <?= nl2br(htmlspecialchars($userResult["introd"])) ?></p>

    <?php endif; ?>


</div>



<!-- ユーザーの投稿一覧 -->

<h2 class="page_titile">ユーザーの投稿一覧</h2>

<?php if (!empty($userPosts)): ?>

  <?php foreach ($userPosts as $post): ?>

    <div class="article board_info" style="padding: 25px 40px; marign: 30px auto;">

      <p>投稿文: <?= nl2br(htmlspecialchars($post["content"])) ?></p>

      <p class="board_datetime" style="font-size: 0.8rem; color: #666;">投稿日時: <?= $post["created_at"] ?></p>
       
      <hr>



    </div>

  

  <?php endforeach; ?>

<?php else: ?>

  <div class="article board_info" style="padding: 25px 40px; margin: 30px auto;">

    <p>投稿がありません</p>

  </div>  

<?php endif; ?>

<div class="back_link">

  <p><a href="board.php">◀ 掲示板に戻る</a></p>

</div>


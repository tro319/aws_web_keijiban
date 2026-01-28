<?php
session_start();

$loginID = $_SESSION["login_id"] ?? "";

if ($loginID == null) {

  header("HTTP/1.1 303 See Other");
  header("Location: ./login.php");
  return;

}


$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (empty($_GET["id"])) {

  echo "投稿が指定されていません";
  return;

}

$postID = intval($_GET["id"]);

$sql = "SELECT board_posts.id,
  board_posts.content,
  board_posts.created_at,
  board_posts.pic_name1,
  board_posts.pic_name2,
  board_posts.pic_name3,
  board_posts.user_id,
  users.id AS user_id,
  users.name,
  users.img_name
  FROM board_posts
  JOIN users ON board_posts.user_id = users.id
  WHERE board_posts.id = :id";

  $stmt = $dbh->prepare($sql);

  $stmt->execute([":id" => $postID]);

  $postResult = $stmt->fetch();





  if (!$postResult) {

    echo "投稿が見つかりません";
    return;

  }


require("./read.php");
require("./header.php");
?>

<div class="container">


<h1 class="page-title">投稿詳細</h1>


<!-- 投稿情報 一件分 -->


<div class="posts-info">

<div class="post-info">

<?php if (!empty($postResult)): ?>

<div class="images-cover post-images-cover">

<ul class="images post-images">

<?php if (isset($postResult["pic_name1"])): ?>



<li>

<div class="image-box">


<img src="/upload/image/<?= htmlspecialchars($postResult["pic_name1"]) ?>">


</div>

</li>


<?php endif; ?>


<?php if (isset($postResult["pic_name2"])): ?>



<li>

<div class="image-box">


<img src="/upload/image/<?= htmlspecialchars($postResult["pic_name2"]) ?>">


</div>

</li>


<?php endif; ?>

<?php if (isset($postResult["pic_name3"])): ?>



<li>

<div class="image-box">


<img src="/upload/image/<?= htmlspecialchars($postResult["pic_name3"]) ?>">


</div>

</li>


<?php endif; ?>




</ul>

</div>


<p>投稿文: <?= nl2br(htmlspecialchars($postResult["content"])) ?></p>

<p class="post-time">投稿日時: <?= date("Y/m/d", strtotime($postResult["created_at"])) ?></p>

<hr>

<div class="user-link">

<a href="user.php?id=<?= $postResult["user_id"] ?>">


<?php if ($postResult["img_name"] != null): ?>

<img src="/upload/image/<?= $postResult["img_name"] ?>" />

<?php else: ?>

<img src="/upload/image/dummy.png" />

<?php endif; ?>


<span><?= htmlspecialchars($postResult["name"]) ?></span>

</a>

<?php endif; ?>


</div>

</div>

</div>

<?php
require("./end.php");

?>

<?php
session_start();

$loginID = $_SESSION["login_id"] ?? "";

if ($loginID == null) {

  header("HTTP/1.1 303 See Other");
  header("Location: ./login.php");
  return;

}


$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

$sql = "SELECT * FROM users WHERE id = :id";

$stmt = $dbh->prepare($sql);

$stmt->execute([":id" => $loginID]);

$profile = $stmt->fetch();


$sql = "SELECT * FROM board_posts WHERE user_id = :user_id ORDER BY created_at DESC";

$stmt = $dbh->prepare($sql);

$stmt->execute(["user_id" => $loginID]);

$profilePosts = $stmt->fetchAll();


require("./read.php");
require("./header.php");
?>

<div class="container">

<h1 class="page-title">プロフィール</h1>


<h2 class="sub-title">プロフィール情報</h2>


<div class="user-info" style="padding: 25px 35px; margin: 30px 45px;">



<?php if(!empty($profile)): ?>

<div class="image-radius">

<?php if(!empty($profile["img_name"])): ?>


<img src="/upload/image/<?= $profile["img_name"] ?>" />   

<?php else: ?>

<img src="/upload/image/dummy.png" />

<?php endif; ?>

</div>



<p>ユーザーネーム: <?= htmlspecialchars($profile["name"]) ?></p>

<p>メールアドレス: <?= htmlspecialchars($profile["email"]) ?></p>

<?php if(!empty($profile["introd"])): ?>

<p>自己紹介: <?= nl2br(htmlspecialchars($profile["introd"])) ?></p>

<?php endif; ?>

<?php endif; ?>

<div class="edit-btn">

<a href="prof_update.php">プロフィール編集</a>

</div>

</div>



<h2 class="sub-title">My投稿一覧</h2>

<div class="post-infos">

<?php if(!empty($profilePosts)): ?>

<?php foreach($profilePosts as $profPost): ?>

<div class="post-info">

<div class="images-cover post-images-cover">

<ul class="images post-images">


<?php if (isset($profPost["pic_name1"])): ?>



<li>

<div class="image-box">


<img src="/upload/image/<?= htmlspecialchars($profPost["pic_name1"]) ?>">


</div>

</li>



<?php endif; ?>


<?php if (isset($profPost["pic_name2"])): ?>



<li>

<div class="image-box">


<img src="/upload/image/<?= htmlspecialchars($profPost["pic_name2"]) ?>">


</div>

</li>



<?php endif; ?>       


<?php if (isset($profPost["pic_name3"])): ?>



<li>

<div class="image-box">


<img src="/upload/image/<?= htmlspecialchars($profPost["pic_name3"]) ?>">


</div>

</li>



<?php endif; ?>   


</ul>

</div>

<p>投稿文: <?= nl2br(htmlspecialchars($profPost["content"])) ?></p>

<p class="post-time">投稿日時: <?= date("Y/m/d", strtotime($profPost["created_at"])) ?></p>

<div class="post-link">

<a href="board_single.php?id=<?= $profPost["id"] ?>">投稿詳細へ</a>

</div>

</div>

<?php endforeach; ?>

<?php endif; ?>


</div>

</div>


<?php
require("./end.php");

?>



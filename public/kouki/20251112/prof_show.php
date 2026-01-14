<?php
session_start();

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

$loginId = $_SESSION["login_id"];

if (empty($loginId)) {

  header("HTTP/1.1 303 See Other");
  header("Location: ./login.php");
  exit;

}


$sql = "SELECT * FROM users WHERE id = :id";

$stmt = $dbh->prepare($sql);

$stmt->execute([":id" => $loginId]);

$profile = $stmt->fetch();


$sql = "SELECT * FROM board_posts WHERE user_id = :user_id ORDER BY created_at DESC";

$stmt = $dbh->prepare($sql);

$stmt->execute(["user_id" => $loginId]);

$profilePosts = $stmt->fetchAll();



?>


<h2 class="page_title">プロフィール</h2>


  <h3 class="sub_title">登録情報</h3>


  <div class="user_info" style="padding: 25px 35px; margin: 30px 45px;">

 
  
    <?php if(!empty($profile)): ?>
      
      <?php if(!empty($profile["img_name"])): ?>

        <div class="prof_img">

          <img src="/upload/image/<?= htmlspecialchars(basename($profile["img_name"])) ?>" style="width: 5em; height: 5em; object-fit:cover; border-radius: 50%;" />   

        </div>

      <?php endif; ?>

      <p>ユーザーネーム: <?= htmlspecialchars($profile["name"]) ?></p>
      
      <?php if(!empty($profile["introd"])): ?>

        <p>自己紹介: <?= nl2br(htmlspecialchars($profile["introd"])) ?></p>

      <?php endif; ?>

    <?php endif; ?>

  </div>


  <h3 class="sub_title">My投稿一覧</h3>

  <?php if(!empty($profilePosts)): ?>

    <?php foreach($profilePosts as $profPost): ?>

      <div class="post_info">

        <p><?= nl2br(htmlspecialchars($profPost["content"])) ?></p>
        <p><?= htmlspecialchars($profPost["created_at"]) ?></p>

      </div>

    <?php endforeach; ?>

  <?php endif; ?>

  <div class="edit_link">

    <a href="profile_edit.php">プロフィール編集へ</a>

  </div>

<?php
session_start();

$loginID = $_SESSION["login_id"];

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



?>


<h2 class="page_title">プロフィール</h2>


  <h3 class="sub_title">登録情報</h3>


  <div class="user-info" style="padding: 25px 35px; margin: 30px 45px;">

 
  
    <?php if(!empty($profile)): ?>
      
      <?php if(!empty($profile["img_name"])): ?>

        <div class="image-radius">

          <img src="/upload/image/<?= htmlspecialchars(basename($profile["img_name"])) ?>" style="width: 5em; height: 5em; object-fit:cover; border-radius: 50%;" />   

        </div>

      <?php endif; ?>

      <p>ユーザーネーム: <?= htmlspecialchars($profile["name"]) ?></p>

      <p>メールアドレス: <?= htmlspecialchars($profile["email"]) ?></p>
      
      <?php if(!empty($profile["introd"])): ?>

        <p>自己紹介: <?= nl2br(htmlspecialchars($profile["introd"])) ?></p>

      <?php endif; ?>

    <?php endif; ?>

  </div>


  <h3 class="sub-title">My投稿一覧</h3>

  <?php if(!empty($profilePosts)): ?>

    <?php foreach($profilePosts as $profPost): ?>

      <div class="post-info">

        <?php if ($profPost["pic_name1"] != null): ?>

        <div class="image-box">

          <img src="/upload/image/<?= $profPost["pic_name1"] ?>" />

        </div>        

        <?php endif; ?>

        <p><?= nl2br(htmlspecialchars($profPost["content"])) ?></p>
        <p><?= htmlspecialchars($profPost["created_at"]) ?></p>

      </div>

    <?php endforeach; ?>

  <?php endif; ?>

  <div class="link-texts">

    <a href="prof_update.php">プロフィール編集へ</a>

    <a href="post.php">投稿する</a>

    <a href="board.php">投稿一覧へ</a>

    <a href="timeline.php">タイムラインへ</a>

  </div>

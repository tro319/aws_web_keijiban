<?php
session_start();

require_once("../library.php");
require_once("myposts.php");


// ログインしているユーザー名が存在しなければ、ログインフォームへ

  if (!empty($_SESSION["log_name"])) {

    $user_name = h_s($_SESSION["log_name"]);

    $posts = get_myposts($user_name);

  } else {
    header("HTTP/1.1 302 Found");
    header("Location: ../index.php");
    exit;

  }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>my投稿一覧 | web掲示板</title>
</head>

<body>

<h2 class="sub_title">my投稿一覧</h2>

<p><a href="../post/form.php">新規投稿</a></p>
<p><a href="../index.php">投稿一覧へ</a></p>
<p><a href="../login/logout.php">ログアウト</a></p>
<p><?php echo $user_name; ?> さんのページ</p>

<div class="posts">

  <?php foreach ($posts as $post): ?>

    <div class="post">

      <h3 class="post_title"><?php echo h_s($post["post_id"]); ?>: <?php echo h_s($post["title"]); ?></h3>

        <?php if (!empty($post["imgfilename"])): ?>

          <div class="post_img" style="width: 100%;">

            <img src="../upload/image/<?php echo h_s($post["imgfilename"]); ?>" alt="投稿画像" style="max-width: 100%" />

          </div>

       <?php endif; ?>

          <div class="post_txts">

            <p><?php echo h_s($post["comment"]); ?></p>

            <?php

              $date = strtotime($post["updated_at"]);

              $format_date = date("Y年m月d日 H時i分", $date);

            ?>

            <p class="post_date"><?php echo h_s($format_date); ?></p>

          </div>

    </div>

  <?php endforeach; ?>

</div>


</body>

</html>



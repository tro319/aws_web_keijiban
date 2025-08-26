<?php
session_start();


require_once("library.php");
require("posts.php");
require("get_username.php");

if (!empty($_SESSION["login_result"])) {

  $result = h_s($_SESSION["login_result"]);

}

// データ取得

$posts = get_posts();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web掲示板</title>
</head>
<body>

<h2 class="sub_title">投稿一覧</h2>
    
 <!-- 投稿一覧表示 -->

 <?php foreach ($posts as $post): ?>

  <div class="post">

   <h3 class="post_title"><?php echo h_s($post["post_id"]); ?>: <?php echo h_s($post["title"]); ?></h3>
    
     <?php if (!empty($post["imgfilename"])): ?>

     <div class="post_img">

      <img src="upload/image/<?php echo h_s($post["imgfilename"]); ?>" alt="投稿画像" />

     </div>

     <?php endif; ?>

    <div class="post_txts">

      <p><?php echo h_s($post["comment"]); ?></p>
      
      <?php 

        $date = strtotime($post["updated_at"]);

        $format_date = date("Y年m月d日 H時i分", $date);

    ?>

    <?php

      $user_name = get_username($post["user_id"]);

   ?>

      <p class="post_username"><?php echo h_s($user_name); ?></p>
      <p class="post_date"><?php echo h_s($format_date); ?></p>
  
    </div>

  </div>

 <?php endforeach; ?>

</body>
</html>


<script>

  window.alert("<?php echo $result; ?>");

</script>




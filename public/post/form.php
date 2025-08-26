<?php
session_start();

require_once("../library.php");

if (!empty($_SESSION["post_error"])) {

 $err = h_s($_SESSION["post_error"]);

}


?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿フォーム | web掲示板</title>
</head>

<body>

  <h2 class="sub_title">投稿フォーム</h2>

  <!-- 投稿フォーム -->

  <form action="post.php" method="post">

    <label for="post_title"><span>タイトル</span>

      <input type="text" name="post_title" id="post_title" maxlength="30" required />

    </label>

    <br>

    <label for="post_text"><span>投稿文</span>

      <input type="text" name="post_text" id="post_text" maxlength="200" required />

    </label>

    <br>

    <input type="submit" value="確認" />

  </form>

</body>
</html>

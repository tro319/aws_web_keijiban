<?php

$redis = new Redis();
$redis->connect("redis", 6379);

$key = "comment_list";

$commentList = $redis->exists($key) ? json_decode($redis->get($key)) : [];


if (!empty($_POST["content_list"])) {

  $comment = $_POST["content_list"];

  array_unshift($commentList, $comment);

  $redis->set($key, json_encode($commentList));

  return header("Location: comment_double.php");

}

?>

<form method="POST" action="">

  <textarea name="content_list" id="content_list" maxlength="500" required></textarea>

  <br>

  <input type="submit" value="更新" />

</form>

<br>

<hr>

<?php foreach ($commentList as $comment): ?>

  <div class="post_content">

    <br>

    <?= nl2br(htmlspecialchars($comment, ENT_QUOTES, "UTF-8")) ?><br>

    <br>

    <hr>

  </div>

<?php endforeach; ?>



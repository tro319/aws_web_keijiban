<?php

$redis = new Redis();

$redis->connect("redis", 6379);

$key = "simple_comment";

$comment = $redis->exists($key) ? $redis->get($key) : "";

if (!empty($_POST["content"])) {

  $comment = htmlspecialchars($_POST["content"], ENT_QUOTES, "UTF-8");
  $redis->set($key, strval($comment));
  return header("Location: ./comment_simple.php");

}


?>

<form method="post" action="">

  <input name="content" id="content" maxlength="30" required />
  <input type="submit" value="送信" />

</form>

<?php if (!empty($comment)): ?>
  
  <p>投稿文 「<?php echo htmlspecialchars($comment, ENT_QUOTES, "UTF-8"); ?>」</p>

<?php endif; ?>

<?php
session_start();

require("../library.php");


if (!empty($_SESSION["post_title"])) {

	$post_title = h_s($_SESSION["post_title"]);

	$post_text = h_s($_SESSION["post_text"]);

  if (!empty($_SESSION["post_imgfilename"])) {

    $post_img = $_SESSION["post_imgfilename"];

  } else {

    $post_img = null;

 }
 
} else {
  header("HTTP/1.1 302 Found");
	header("Location: form.php");
	exit;

}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿確認 | web掲示板</title>
</head>

<body>

	<h2 class="sub_title">投稿確認</h2>

	<form action="entry.php" method="post">

		<label for="post_title"><span>タイトル: <?php echo $post_title; ?></span>
		
			<input type="hidden" name="post_title" id="post_title" value="<?php echo $post_title; ?>" required />

		</label>

		<br>

		<label for="post_text"><span>投稿文: <?php echo $post_text; ?></span>
	
			<input type="hidden" name="post_text" id="post_text" value="<?php echo $post_text; ?>" required />

		</label>

    <br>
<?php if ($post_img !== null): ?>

    <label for="post_img"><span>画像: <img src="../upload/image/<?php echo $post_img; ?>" alt="画像投稿確認" ></span>

      <input type="hidden" name="post_img" id="post_img" value="<?php echo $post_img; ?>" />

    </label>

		<br>

<?php endif; ?>

		<button type="button" onclick="location.href='form.php'">修正</button>

		<input type="submit" value="投稿" />

	</form>

</body>

</html>

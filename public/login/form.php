<?php
session_start();

require("../library.php");


if (!empty($_SESSION["login_error"])) {

	$err = h_s($_SESSION["login_error"]);

}


?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン| web掲示板</title>
</head>
<body>

  <!-- エラー表示 -->

  <?php if (!empty($err)): ?>

    <p class="err"><?php echo $err ?></p>

  <?php endif; ?>

  <!-- 入力フォーム -->

	<form action="login.php" method="post">

		<label for="log_email"><span>メールアドレス</span>
			
			<input type="email" name="log_email" id="log_email" maxlength="255" required />

		</label>

		<br>

		<label for="log_pass"><span>パスワード</span>

			<input type="password" name="log_pass" id="log_pass" maxlength="30" required />

		</label>

		<br>

		<input type="submit" value="ログイン" />

	</form>
    
</body>
</html>

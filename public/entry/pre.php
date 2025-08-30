<?php

session_start();

require("../library.php");

if (!empty($_SESSION["log_name"])) {
  header("HTTP/1.1 302 Found");
  header("Location: ../index.php");
  exit;

} else if (empty($_SESSION["entry_name"])) {
  header("HTTP/1.1 302 Found");
  header("Location: ../index.php");
  exit;

}

$user_name = h_s($_SESSION["entry_name"]);

$email = h_s($_SESSION["entry_email"]);

$pass = password_hash(h_s($_SESSION["entry_pass"]), PASSWORD_DEFAULT);



?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録確認 | web掲示板</title>
</head>

<body>


<form method="post" action="entry.php">

  <label for="entry_name">

    <input type="hidden" name="entry_name" id="entry_name" value="<?php echo $user_name; ?>" /><p>ユーザーネーム: <?php echo $user_name; ?></p>

  </label>

  <label for="entry_email">

    <input type="hidden" name="entry_email" id="entry_email" value="<?php echo $email; ?>" /><p>メールアドレス: <?php echo $email; ?></p>

  </label>

  <label for="entry_pass">

    <input type="hidden" name="entry_pass" id="entry_pass" value="<?php echo $pass; ?>" /><p>パスワード: 表示されません</p>

  </label>

  <button type="button" onclick="location.href='form.php'">修正</button>
  <input type="submit" value="登録" />

</form>

</body>

</html>

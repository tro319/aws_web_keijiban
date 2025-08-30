<?php
session_start();

require("../library.php");

if (!empty($_SESSION["user_error"])) {

  $err = h_s($_SESSION["user_error"]);

}

if (!empty($_SESSION["log_name"])) {
  header("HTTP/1.1 302 Found");
  header("Location: ../index.php");
  exit;

}


// セッションデータがあるか確認

$user_name = null;

$email = null;

if (!empty($_SESSION["entry_email"]) AND !empty($_SESSION["entry_name"])) {

  $user_name = h_s($_SESSION["entry_name"]);

  $email = h_s($_SESSION["entry_email"]);

}



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録フォーム | web掲示板</title>
</head>

<body>

  <!-- エラー表示 -->

  <?php if (!empty($err)): ?>

    <p class="err"><?php echo $err; ?></p>

    <?php unset($_SESSION["user_error"]) ?>

  <?php endif; ?>

  <!-- 入力フォーム -->

  <form method="post" action="confirm.php">

    <label for="entry_name"><span>ユーザーネーム</span>

      <input type="text" name="entry_name" id="entry_name" maxlength="30" value="<?php echo h_s($user_name ?? ''); ?>" required />

    </label>

    <br>

    <label for="entry_email"><span>メールアドレス</span>

      <input type="email" name="entry_email" id="entry_email" maxlength="255" value="<?php echo h_s($email ?? ''); ?>" required />
    
    </label>
    
    <br>

    <label for="entry_pass"><span>パスワード</span>

      <input type="password" name="entry_pass" id="entry_pass" maxlength="30" required />

    </label>

    <br>

    <input type="submit" value="確認" />

  </form>

  <p><a href="../login/form.php">ログイン</a></p>
  <p><a href="../index.php">投稿一覧へ</a></p>

</body>

</html>



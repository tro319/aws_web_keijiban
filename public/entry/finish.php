<?php
session_start();

require("../library.php");

if (!empty($_SESSION["log_name"])) {
  header("Location: ../index.php");
  exit;
} else if (empty($_SESSION["entry_result"])) {
  header("Location: ../index.php");
  exit;
 
}


$result = h_s($_SESSION["entry_result"]);

session_destroy();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録結果 | web掲示板</title>
</head>

<body>

	<p><?php echo $result; ?></p>
  <p><a href="../login/form.php">ログイン</a></p>

</body>

</html>

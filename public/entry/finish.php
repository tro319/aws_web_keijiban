<?php
session_start();

require("../library.php");

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

</body>

</html>

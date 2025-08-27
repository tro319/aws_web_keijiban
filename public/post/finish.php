<?php
session_start();


require_once("../library.php");

if (!empty($_SESSION["log_name"])) {

  if (!empty($_SESSION["post_result"])) {

    $result = h_s($_SESSION["post_result"]);

  }

} else {
  header("Location: ../index.php");
  exit;

}

unset($_SESSION["post_title"]);
unset($_SESSION["post_text"]);

?>

<p><?php echo $result; ?></p>

<p><a href="../index.php">投稿一覧へ</a></p>

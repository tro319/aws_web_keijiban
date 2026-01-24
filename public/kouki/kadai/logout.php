<?php
session_start();
unset($_SESSION["login_id"]);

header("HTTP/1.1 303 See Other");
header("Location: ./login.php");
return;

?>

<?php
  
  require("../library.php");

  if ($_SERVER["method"] == "post") {

    $_SESSION["user_name"] = h_s($_POST["user_name"]);
    $_SESSION["user_email"] = h_s($_POST["user_email"]);
    $_SESSION["user_pass"] = password_hash($_POST["user_pass"], PASSWORD_DEFAULT);
    







  }



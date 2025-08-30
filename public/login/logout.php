<?php
session_start();

  require_once("../library.php");

  if (empty($_SESSION["log_name"])) {
    header("HTTP/1.1 302 Found");
    header("Location: ../index.php");
    exit;

  } else if (!empty($_SESSION["log_name"])) {

    session_destroy();

  }
  header("HTTP/1.1 302 Found");
  header("Location: ../index.php");
  exit;

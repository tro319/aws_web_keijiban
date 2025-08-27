<?php
session_start();

  require_once("../library.php");

  if (empty($_SESSION["log_name"])) {
    header("Location: ../index.php");
    exit;

  } else if (!empty($_SESSION["log_name"])) {

    session_destroy();

  }
  header("Location: ../index.php");
  exit;

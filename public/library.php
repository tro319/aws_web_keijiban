<?php

  function h_s($text) {

    return htmlspecialchars($text, ENT_QUOTES, "UTF-8");

  }


  function db() {

   	$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", ""); 

    return $dbh;
  
  }

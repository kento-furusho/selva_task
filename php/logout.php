<?php
  ini_set('display_errors', 'on');
  session_start();
  $_SESSION = array();
  header("Location:index.php");
?>
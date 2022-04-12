<?php
  if(!function_exists('h')) {
    function h($s) {
      echo htmlspecialchars($s, ENT_QUOTES, "UTF-8");
    }
  }
  function db_connect() {
    $option = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
    );
    $pdo = new PDO('mysql:charset=utf8mb4;dbname=selva_task;host=localhost', 'root', 'pass7610', $option);
    if($pdo) {
      return $pdo;
    }
  }
?>
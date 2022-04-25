<?php
  session_start();
  require_once('../function.php');
  ini_set('display_errors', 'on');
  date_default_timezone_set('Asia/Tokyo');
  $id = $_GET['id'];
  $current_date = date("Y-m-d H:i:s");
  try {
    $pdo = db_connect();
    $prepare = $pdo->prepare("UPDATE members SET deleted_at = :current_date WHERE id = :id");
    if($prepare) {
      $prepare->bindParam(':current_date', $current_date, PDO::PARAM_STR);
      $prepare->bindValue(':id', $id, PDO::PARAM_INT);
      if($prepare->execute()) {
        header('location:members.php');
        exit;
      }
    }
    $pdo = null;
  } catch(PDOException $e) {
    $delete_err_msg[] = $e->getMessage();
  };
?>
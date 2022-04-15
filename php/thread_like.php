<?php
// 開発者モード
ini_set('display_errors', 'on');
session_start();
require_once('function.php');
if(empty($_SESSION['loggedin'])) {
  header("location:member_regist.php");
} else {
  if(!empty($_POST)) {
    $member_id = $_POST['member_id'];
    $comment_id = $_POST['comment_id'];
    $thread_id = $_POST['thread_id'];
    $page_num = $_POST['page_num'];
    try {
      // 接続、いいね情報ゲット
      $pdo = db_connect();
      $query = $pdo->query("SELECT * FROM likes WHERE member_id = $member_id and comment_id = $comment_id");
      $res = $query->fetch(PDO::FETCH_ASSOC);
      // テーブルにあれば削除、なければ登録
      if($res) {
        $res = $pdo->exec("DELETE FROM likes WHERE member_id = $member_id and comment_id = $comment_id");
      } else {
        $res = $pdo->exec("INSERT INTO likes (member_id, comment_id) VALUES ($member_id, $comment_id)");
      }
    } catch(PDOException $e) {
      var_dump($e->getMessage());
    }
    header('location:thread_detail.php?id='.$thread_id.'&page='.$page_num);
  }
}
?>
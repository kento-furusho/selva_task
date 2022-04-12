<?php
require_once('function.php');
// 開発者モード
ini_set('display_errors', 'on');
session_start();
date_default_timezone_set('Asia/Tokyo');
// 空のエラー文
$detail_err_msg = array();
///// 個々スレッド情報獲得 /////
  if(isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
      // 接続
      $pdo = db_connect();
      //SQL作成
      $prepare = $pdo->prepare("SELECT member_id, title, content, created_at FROM threads WHERE id = $id");
      // 実行
      if($prepare->execute()) {
        $res = $prepare->fetch();
        // 結果代入
        $title = $res['title'];
        $content = $res['content'];
        $created_at = $res['created_at'];
      }
      ///// スレッドの作成者情報獲得 /////
      $member_id = $res['member_id'];
      $prepare = $pdo->prepare("SELECT id, name_sei, name_mei FROM members WHERE id = $member_id ORDER BY created_at DESC");
      // 実行
      if($prepare->execute()) {
        $name_res = $prepare->fetch();
        $name_sei = $name_res['name_sei'];
        $name_mei = $name_res['name_mei'];
      }
      ///// スレッドごとのコメント獲得(5件ずつ) /////
      // 現在のページ数取得
      if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
      } else {
        $page = 1;
      }
      // スタートポジション計算
      if ($page > 1) {
        $start = ($page * 5) - 5;
      } else {
        $start = 0;
      }
      $prepare = $pdo->prepare("SELECT id, member_id, thread_id, comment, created_at FROM comments WHERE $id = thread_id ORDER BY created_at ASC LIMIT $start, 5");
      if($prepare->execute()) {
        while($comment = $prepare->fetch()) {
          $comments[] = $comment;
        }
      }
      // 総コメント数取得
      $prepare = $pdo->prepare("SELECT * FROM comments WHERE $id = thread_id ");
      $prepare->execute();
      $count = $prepare->rowCount();
      // 総ページ数
      $page_num = ceil($count / 5);
      ///// コメントごとの作成者情報獲得 /////
      if(!empty($comments)) {
        $prepare = $pdo->prepare("SELECT id, name_sei, name_mei FROM members WHERE (:member_id) = id ORDER BY created_at ASC");
        foreach ($comments as $comment) {
          $prepare->bindValue(':member_id', $comment['member_id'], PDO::PARAM_INT);
        }
        if($prepare->execute()) {
          while($member = $prepare->fetch()) {
            $members[] = $member;
          }
        }
      }
        // 接続解除
      $prepare = null;
      $pdo = null;
    } catch(PDOException $e) {
      $detail_err_msg[] = $e->getMessage();
    }
  }
  /////////// コメント登録 //////////
  if(!empty($_POST)) {
    $comment_err_msg = array();
    $current_data = date("Y-m-d H:i:s");
    // バリデーション
    function comment_validation($data) {
      $err_msg = array();
      if (empty($data['comment'])) {
        $err_msg[] = 'コメントを入力してください';
      }
      if (mb_strlen($data['comment']) > 500) {
        $err_msg[] = 'コメントは500文字以内で入力してください';
      }
      return $err_msg;
    }
    // バリデーション実行
    $comment_err_msg = comment_validation($_POST);
    // SQL接続〜コメント投稿
    if(empty($comment_err_msg)) {
      try {
        // 接続
        $pdo = db_connect();
        // SQL
        $prepare = $pdo->prepare(
          "INSERT INTO comments (member_id, thread_id, comment, created_at, updated_at) VALUES (:member_id, :thread_id, :comment, :created_at, :updated_at)"
        );
        // 値のセット
        $prepare->bindValue(':member_id', $_SESSION['member_id'], PDO::PARAM_INT);
        $prepare->bindValue(':thread_id', $id, PDO::PARAM_INT);
        $prepare->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
        $prepare->bindParam( ':created_at', $current_data, PDO::PARAM_STR);
        $prepare->bindParam( ':updated_at', $current_data, PDO::PARAM_STR);
        // 実行
        $prepare->execute();
        
        // 接続解除
        $pdo = null;
        $prepare = null;
      } catch(PDOException $e) {
        $comment_err_msg[] = $e->getMessage();
      }
      header("Location:thread_detail.php?id=".$id.'&page='.$page_num);
      exit();
    }
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>スレッド詳細</title>
</head>
<body style="background-color:#E2EDF6;">
  <!-- ヘッダー -->
  <header>
    <div class="header-right">
      <a class="header-btn right-side-btn" href="thread.php">スレッド一覧に戻る</a>
    </div>
  </header>
  <div>
    <h3 class="thread_title">
      <?= $title ?>
    </h3>
  </div>
  <div class="thread_com">
    <p>
      <?php echo $count.'コメント' ?>
    </p>
  </div>
  <div class="thread_date">
    <p>
      <?= date('m/d/y H:i', strtotime($created_at)) ?>
    </p>
  </div>

  <!-- 灰色の部分読み込み -->
  <?php include('_thread_pagination.php')?>

  <!-- スレッド詳細 -->
  <div class="thread_detail_main">
    <p>
      投稿者:<?php echo $name_sei.' '.$name_mei.' '.date('Y.m.d H:i', strtotime($created_at)) ?>
    </p>
    <p>
      <?php echo nl2br(htmlspecialchars($content)) ?>
    </p>
  </div>

  <!-- コメント表示 -->
  <?php if(!empty($comments)) :?>
    <?php foreach($comments as $comment):?>
      <?php foreach($members as $member):?>
        <?php
          $comment_id = $comment['id'];
          $comment_name = $member['name_sei'].$member['name_mei'];
          $comment_date = date('m/d/y H:i', strtotime($comment['created_at']));
          $comment_content = $comment['comment'];
        ?>
          <div class="comments">
            <p><?=$comment_id.'.'.' '.$comment_name.' '.$comment_date ?></p>
            <p><?=$comment_content?></p>
          </div>
      <?php endforeach?>
    <?php endforeach?>
  <?php endif ?>

  <!-- 灰色の部分読み込み -->
  <?php include('_thread_pagination.php')?>

  <!-- ログイン時表示:コメント入力欄 -->
  <?php if(!empty($_SESSION['loggedin'])) : ?>
    <div class="comment_form">
      <form action="thread_detail.php?id=<?= $id?>" method="post">
        <textarea name="comment"></textarea>
        <br>
        <!-- エラー文 -->
        <div class="comment_err_msg">
          <?php
            if(!empty($comment_err_msg)) {
              foreach ($comment_err_msg as $err_msg) {
                echo '※'.$err_msg.'<br/>';
              }
            }
          ?>
        </div>
        <input class="btn" type="submit" value="コメントする">
      </form>
    </div>
  <?php endif ?>
</body>
</html>
<?php
// 開発者モード
ini_set('display_errors', 'on');
session_start();
// 空のエラー文
$detail_err_msg = array();
// 個々スレッド情報獲得
  if(isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
      // 接続
      $option = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
      );
      $pdo = new PDO('mysql:charset=utf8mb4;dbname=selva_task;host=localhost', 'root', 'pass7610', $option);
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
      // スレッドの作成者情報獲得
      $member_id = $res['member_id'];
      $prepare = $pdo->prepare("SELECT id, name_sei, name_mei FROM members WHERE id = $member_id");
      // 実行
      if($prepare->execute()) {
        $name_res = $prepare->fetch();
        $name_sei = $name_res['name_sei'];
        $name_mei = $name_res['name_mei'];
      }
    } catch(PDOException $e) {
      $detail_err_msg[] = $e->getMessage();
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
  <div class="thread_date">
    <p>
      <?= date('m/d/y H:i', strtotime($created_at)) ?>
    </p>
  </div>
  <div class="thread_gray">
    <!-- 灰色の部分 -->
  </div>
  <div class="thread_detail_main">
    <p>
      投稿者:<?php echo $name_sei.' '.$name_mei.' '.date('Y.m.d H:i', strtotime($created_at)) ?>
    </p>
    <p>
      <?php echo nl2br(htmlspecialchars($content)) ?>
    </p>
  </div>
  <div class="thread_gray">
    <!-- 灰色の部分 -->
  </div>
  <!-- ログイン時表示:コメント入力欄 -->
  <?php if(!empty($_SESSION['loggedin'])) : ?>
    <div class="comment_form">
      <form action="thread_detail.php" method="post">
        <textarea name="comment"></textarea>
        <br>
        <input class="btn" type="submit" value="コメントする">
      </form>
    </div>
  <?php endif ?>
</body>
</html>
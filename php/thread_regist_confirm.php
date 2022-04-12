<?php
session_start();
ini_set('display_errors', 'on');
require_once('function.php');
date_default_timezone_set('Asia/Tokyo');
  if(!empty($_POST)) {
    $thread_err_msg = null;
    $pdo = null;
    $stmt = null;
    $res = null;
    // 日時取得
    $current_data = date("Y-m-d H:i:s");
    // 接続
    try {
      $option = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
      );
        $pdo = new PDO('mysql:charset=utf8mb4;dbname=selva_task;host=localhost', 'root', 'pass7610', $option);
        // sql作成
        $stmt = $pdo->prepare(
          "INSERT INTO threads (member_id, title, content, created_at, updated_at)
      VALUES (:member_id, :title, :content, :created_at, :updated_at)"
      );
      // 値のセット
      $stmt->bindValue( ':member_id', $_POST['member_id'], PDO::PARAM_INT);
      $stmt->bindParam( ':title', $_POST['title'], PDO::PARAM_STR);
      $stmt->bindParam( ':content', $_POST['comment'], PDO::PARAM_STR);
      $stmt->bindParam( ':created_at', $current_data, PDO::PARAM_STR);
      $stmt->bindParam( ':updated_at', $current_data, PDO::PARAM_STR);
      // 実行
      $res = $stmt->execute();

      // プリペアドステートメントを削除
      $stmt = null;
      // データベースの接続を閉じる
      $pdo = null;
    } catch(PDOException $e) {
      $thread_err_msg[] = $e->getMessage();
    }
      if($res) {
        header("Location:thread.php");
      } else {
        $thread_err_msg[] = 'スレッドを投稿できませんでした';
      }
      // セッション空にする
      unset($_SESSION['title']);
      unset($_SESSION['comment']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>スレッド作成確認画面</title>
</head>
<body>
  <div class="container">
    <h2>スレッド作成確認画面</h2>
    <div class="err_msg">
          <?php
            if(!empty($thread_err_msg)) {
              foreach ($thread_err_msg as $err_msg) {
                echo '※'.$err_msg.'<br/>';
              }
            }
          ?>
        </div>
      <form method="post" action="thread_regist_confirm.php">
        <div class='content'>
          <input type="hidden" name="member_id" value="<?php echo $_SESSION['member_id']?>">
          <input type="hidden" name="title" value="<?php echo $_SESSION['title']?>">
          <input type="hidden" name="comment" value="<?php echo $_SESSION['comment']?>">
          <p>スレッドタイトル
            <?php echo h($_SESSION['title'])?>
          </p>
          <p>コメント
            <?php
             echo nl2br(htmlspecialchars($_SESSION['comment']))
            ?>
          </p>
        </div>
        <div class='btn-container'>
          <a>
            <input class="btn" type="submit" value="スレッドを作成する">
          </a>
        </div>
        <div class='btn-container'>
          <a href="thread_regist.php">
            <input class="back_btn" type="button" value="前に戻る">
          </a>
        </div>
      </form>
  </div>
</body>
</html>
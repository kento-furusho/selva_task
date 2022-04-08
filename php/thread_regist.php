<?php
// 開発者モード
ini_set('display_errors', 'on');
// require_once('validate.php');


  session_start();
  if(!empty($_POST)){
    // 一旦入れる
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['comment'] = $_POST['comment'];
    
    // バリデーション
    function thread_validation($data) {
      $err_msg = array();
      // タイトル
      if (empty($data['title'])) {
        $err_msg[] = 'スレッドタイトルを入力してください';
      }
      if (mb_strlen($data['title']) > 100) {
        $err_msg[] = 'スレッドタイトルは100文字以内で入力してください';
      }
      // コメント
      if (empty($data['comment'])) {
        $err_msg[] = 'コメントを入力してください';
      }
      if (mb_strlen($data['comment']) > 500) {
        $err_msg[] = 'コメントは500文字以内で入力してください';
      }
      return $err_msg;
    };
    $err_messages = thread_validation($_POST);

    if (empty($err_messages)) {
      header("Location:thread_regist_confirm.php");
      exit;
    }
  }

    // エラー後、入力保持用
    if(!empty($_SESSION)) {
      $title = $_SESSION['title'];
      $comment = $_SESSION['comment'];
    }
    // セッション空にする
    $_SESSION = array();
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>スレッド作成フォーム</title>
</head>
<body>
  <div class="container">
    <form class='forms' method="post" action="thread_regist.php">
        <h2>スレッド作成フォーム</h2>
        <div class="err_msg" style="margin: 0; text-align:center ;">
          <?php
            if(!empty($err_messages)) {
              foreach ($err_messages as $err_msg) {
                echo '※'.$err_msg.'<br/>';
              }
            }
          ?>
        </div>
        <p>
          <label for="title">スレッドタイトル</label>
          <input class='title' type="text" name='title' id='title' value="<?php if(!empty($title) ){ echo $title; } ?>">
        </p>
        <p>
          <label for="comment">コメント</label>
          <textarea class="comment" name="comment" id="comment" cols="30" rows="10"><?php if (!empty($comment)){ print $comment; } ?></textarea>
        </p>
        <div class='btn-container'>
          <a>
            <input class="btn" type="submit" value="確認画面へ">
          </a>
        </div>
        <div class='btn-container'>
          <a href="index.php">
            <input class="back_btn" type="button" value="トップに戻る">
          </a>
        </div>
      </form>
  </div>
</body>
</html>
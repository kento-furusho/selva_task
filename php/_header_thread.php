<?php
  ini_set('display_errors', 'on');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>スレッドヘッダー</title>
</head>
<body style="background-color:#E2EDF6;">
  <header>
    <!-- ログイン時 -->
    <?php if(!empty($_SESSION['loggedin'])) : ?>
      <div class="header-right">
        <a class="header-btn right-side-btn" href="thread_regist.php">新規スレッド作成</a>
      </div>
    <?php endif ?>
    <!-- ログアウト時 -->
  </header>
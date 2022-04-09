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
  <title>トップページ</title>
</head>
<body>
  <header>
    <!-- ログイン時 -->
    <?php if(!empty($_SESSION['loggedin'])) : ?>
      <div class="header-left">
        <p class="welcome-msg">ようこそ
          <?php echo $_SESSION['name_sei'].$_SESSION['name_mei']?>
          様
        </p>
      </div>
      <div class="header-right">
        <a class="header-btn" href="thread_regist.php">新規スレッド作成</a>
        <a class="header-btn right-side-btn" href="logout.php">ログアウト</a>
      </div>
    <?php endif ?>
    <!-- ログアウト時 -->
    <?php if(empty($_SESSION['loggedin'])): ?>
      <div class="header-left">
       
      </div>
      <div class="header-right">
        <a class="header-btn" href="member_regist.php">新規会員登録</a>
        <a class="header-btn right-side-btn" href="login.php">ログイン</a>
      </div>
    <?php endif ?>
  </header>
</body>
</html>
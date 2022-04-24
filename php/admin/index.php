<?php
// 開発者モード
ini_set('display_errors', 'on');
session_start();
if(empty($_SESSION['admin_loggedin'])) {
  header('location:login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <title>管理画面トップ画面</title>
</head>
<body style="background-color:#E2EDF6;">
  <header class="admin_header">
    <!-- ログイン時 -->
    <?php if(!empty($_SESSION['admin_loggedin'])) : ?>
      <div class="header-left">
        <h3 style="line-height:60px; margin-left:20px;">
          掲示板管理画面メインメニュー
        </h3>
      </div>
      <div class="header-right">
        <span style="margin-right: 15px;" class="welcome-msg">ようこそ
          <?php echo $_SESSION['name'] ?>
          さん
        </span>
        <a class="header-btn right-side-btn" href="logout.php">ログアウト</a>
      </div>
    <?php endif ?>
  </header>
  <main>
      <div style="margin-top:60px; text-align:center;">
        <a class="back_btn members_btn" href="members.php">会員一覧</a>
      </div>
  </main>
</body>
</html>
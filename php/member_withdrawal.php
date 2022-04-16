<?php
  session_start();
  require_once('function.php');
  ini_set('display_errors', 'on');
  date_default_timezone_set('Asia/Tokyo');
  if(!empty($_POST['member_id'])) {
    $member_id = $_POST['member_id'];
    $current_date = date("Y-m-d H:i:s");
    $pdo = db_connect();
    $prepare = $pdo->prepare("UPDATE members SET deleted_at = :current_date WHERE id = :member_id");
    if($prepare) {
      $prepare->bindParam(':current_date', $current_date, PDO::PARAM_STR);
      $prepare->bindValue(':member_id', $member_id, PDO::PARAM_INT);
      if($prepare->execute()) {
        $_SESSION = array();
        header('location:index.php');
      }
    }
    $pdo = null;
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>退会ページ</title>
</head>
<body style="background-color:#E2EDF6;">
  <header>
    <div class="header-right">
      <a class="header-btn right-side-btn" href="index.php">トップに戻る</a>
    </div>
  </header>
  <main style="text-align: center;">
    <h3 style="padding:20px;">退会</h3>
    <p style="padding:15px;">
      退会しますか？
    </p>
    <form style="padding:30px;" action="member_withdrawal.php" method="post">
      <input type="hidden" name="member_id" value="<?=$_SESSION['member_id']?>">
      <button style="padding:8px 26px;" class="btn signout" type="submit">
        退会する
      </button>
    </form>
  </main>
</body>
</html>
<?php
  session_start();

  $_SESSION['family_name'] = $_POST['family_name'];
  $_SESSION['given_name'] = $_POST['given_name'];
  $_SESSION['gender'] = $_POST['gender'];
  $_SESSION['pref_name'] = $_POST['pref_name'];
  $_SESSION['last_address'] = $_POST['last_address'];
  $_SESSION['mail'] = $_POST['mail'];

  $family_name = $_SESSION['family_name'];
  $given_name = $_SESSION['given_name'];
  $gender = $_SESSION['gender'];
  $pref_name = $_SESSION['pref_name'];
  $last_address = $_SESSION['last_address'];
  $mail = $_SESSION['mail'];

  var_dump($family_name);
  
  // header("Location: member_regist_confirm.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>会員情報確認画面</title>
</head>
<body>
  <div class="container">
    <div class='content'>
      <h2>会員情報確認画面</h2>
        <p>氏名
          <?php echo $_POST['family_name']?>
          <?php echo $_POST['given_name']?>
        </p>
        <p>性別
          <?php echo $_POST['gender']?>
        </p>
        <p>住所
          <?php echo $_POST['pref_name']?>
          <?php echo $_POST['last_address']?>
        </p>
        <p>パスワード
          <?php echo 'セキュリティのため非表示'?>
        </p>
        <p>メールアドレス
          <span style='color: #6495ed;'>
            <?php echo $_POST['mail']?>
          </span>
        </p>
      </div>
      <div class='btn-container'>
        <a href="member_regist_completed.php">
          <input class="btn" type="submit" value="登録完了">
        </a>
      </div>
      <div class='btn-container'>
        <a href="member_regist.php">
          <input class="back_btn" type="submit" value="前に戻る">
        </a>
      </div>
  </div>
</body>
</html>
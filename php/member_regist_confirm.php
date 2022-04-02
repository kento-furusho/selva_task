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
        <p style='color: #6495ed;'>メールアドレス
          <?php echo $_POST['mail']?>
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
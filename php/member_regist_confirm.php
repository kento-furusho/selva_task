<?php
date_default_timezone_set('Asia/Tokyo');
session_start();
  if(!empty($_POST)) {
    $db_err_msg = null;
    $pdo = null;
    $stmt = null;
    $res = null;
    // 日時取得
    $current_data = date("Y-m-d H:i:s");
    // 接続
    try {
      $pdo = new PDO('mysql:charset=UTF8;dbname=selva_task;host=localhost', 'root', 'pass7610');
    } catch(PDOException $e) {
      $db_err_msg[] = $e->getMessage();
    }
    // sql作成
    $stmt = $pdo->prepare(
      "INSERT INTO members (name_sei, name_mei, gender, pref_name, address, password, email, created_at, updated_at)
      VALUES (:name_sei, :name_mei, :gender, :pref_name, :address, :password, :email, :created_at, :updated_at)"
      );
      // 値のセット
      $stmt->bindParam( ':name_sei', $_POST['name_sei'], PDO::PARAM_STR);
      $stmt->bindParam( ':name_mei', $_POST['name_mei'], PDO::PARAM_STR);
      $stmt->bindValue( ':gender', $_POST['gender'], PDO::PARAM_INT);
      $stmt->bindParam( ':pref_name', $_POST['pref_name'], PDO::PARAM_STR);
      $stmt->bindParam( ':address', $_POST['address'], PDO::PARAM_STR);
      $stmt->bindParam( ':password', $_POST['password'], PDO::PARAM_STR);
      $stmt->bindParam( ':email', $_POST['email'], PDO::PARAM_STR);
      $stmt->bindParam( ':created_at', $current_data, PDO::PARAM_STR);
      $stmt->bindParam( ':updated_at', $current_data, PDO::PARAM_STR);
      // 実行
      $res = $stmt->execute();

      // プリペアドステートメントを削除
      $stmt = null;
      $pdo = null;
      if($res) {
        header("Location:member_regist_completed.php");
      } else {
        $db_err_msg[] = '登録に失敗しました。';
      }
  }
  // データベースの接続を閉じる
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
    <h2>会員情報確認画面</h2>
    <div class="err_msg">
          <?php
            if(!empty($db_err_msg)) {
              foreach ($db_err_msg as $err_msg) {
                echo '※'.$err_msg.'<br/>';
              }
            }
          ?>
        </div>
      <form method="post" action="member_regist_confirm.php">
        <div class='content'>
          <input type="hidden" name="name_sei" value="<?php echo $_SESSION['name_sei']?>">
          <input type="hidden" name="name_mei" value="<?php echo $_SESSION['name_mei']?>">
          <input type="hidden" name="gender" value="<?php echo $_SESSION['gender']?>">
          <input type="hidden" name="pref_name" value="<?php echo $_SESSION['pref_name']?>">
          <input type="hidden" name="address" value="<?php echo $_SESSION['address']?>">
          <input type="hidden" name="password" value="<?php echo $_SESSION['password']?>">
          <input type="hidden" name="email" value="<?php echo $_SESSION['email']?>">
          <p>氏名
            <?php echo $_SESSION['name_sei']?>
            <?php echo $_SESSION['name_mei']?>
          </p>
          <p>性別
            <?php
             if($_SESSION['gender'] === '1') {
                echo "男性";
             } elseif ($_SESSION['gender'] === '2') {
                echo "女性";
             }
            ?>
          </p>
          <p>住所
            <?php echo $_SESSION['pref_name']?>
            <?php echo $_SESSION['address']?>
          </p>
          <p>パスワード
            <?php echo 'セキュリティのため非表示'?>
          </p>
          <p>メールアドレス
            <span style='color: #6495ed;'>
              <?php echo $_SESSION['email']?>
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
            <input class="back_btn" type="button" value="前に戻る">
          </a>
        </div>
      </form>
  </div>
</body>
</html>
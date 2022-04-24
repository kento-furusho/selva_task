<?php
// 開発者モード
ini_set('display_errors', 'on');
require_once('validate.php');
require_once('function.php');

  session_start();
  if(!empty($_POST)){
    $_SESSION['name_sei'] = $_POST['name_sei'];
    $_SESSION['name_mei'] = $_POST['name_mei'];
    $_SESSION['gender'] = $_POST['gender'];
    $_SESSION['pref_name'] = $_POST['pref_name'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['re_password'] = $_POST['re_password'];
    $_SESSION['email'] = $_POST['email'];

    // バリデーション
    $err_messages = validation($_POST, $prefectures);

    if (empty($err_messages)) {
      header("Location:member_regist_confirm.php");
      exit;
    }
  }

    // エラー後、入力保持用
    if(!empty($_SESSION)) {
      $name_sei = $_SESSION['name_sei'];
      $name_mei = $_SESSION['name_mei'];
      $gender = $_SESSION['gender'];
      $pref_name = $_SESSION['pref_name'];
      $address = $_SESSION['address'];
      $password = $_SESSION['password'];
      $re_password = $_SESSION['re_password'];
      $email = $_SESSION['email'];
    }

    $_SESSION = array();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>会員情報登録フォーム</title>
</head>
<body>
  <div class="container">
    <form class='forms' method="post" action="member_regist.php">
        <h2>会員情報登録フォーム</h2>
        <div class="err_msg">
          <?php
            if(!empty($err_messages)) {
              foreach ($err_messages as $err_msg) {
                echo '※'.$err_msg.'<br/>';
              }
            }
          ?>
        </div>
        <p>氏名
          <label for="name_sei">姓</label>
          <input class='input_name' type="text" name='name_sei' id='name_sei' value="<?php if(!empty($name_sei) ){ echo h($name_sei); } ?>">
          <label for="name_mei">名</label>
          <input class='input_name' type="text" name='name_mei' id='name_mei' value="<?php if(!empty($name_mei) ){ echo h($name_mei); } ?>">
        </p>
        <p>性別
          <input style="margin-left: 20px;" type="radio" name='gender' value='1'
          <?php if (isset($gender) && $gender == '1') echo 'checked'; ?>>男性
          <input type="radio" name='gender' value='2'
          <?php if (isset($gender) && $gender == '2') echo 'checked'; ?>>女性
        </p>
        <p>住所
          <label>都道府県</label>
          <select name="pref_name">
            <option value="0">選択してください</option>
            <?php foreach($prefectures as $prefecture): ?>
                <option value="<?php echo $prefecture ?>"
                <?php if(!empty($pref_name) && $prefecture === $pref_name) echo 'selected'; ?>><?php echo $prefecture ?>
            <?php endforeach; ?>
          </select>
        </p>
        <p style="margin-left: 38px;">
          <label for="address" class="address_label">それ以降の住所</label>
          <input style="width: 237px;" type="text" name='address' id='name_sei' value="<?php if(!empty($address) ){ echo h($address); } ?>">
        </p>
        <p>
          <label for="password">パスワード</label>
          <input style="margin-left: 37px;" class='form_last_3' type="password" name='password' id='password'>
        </p>
        <p>
          <label for="re_password">パスワード確認</label>
          <input style="margin-left: 5px;" class='form_last_3' type="password" name='re_password' id='re_password'>
        </p>
        <p>
          <label for="email">メールアドレス</label>
          <input style="margin-left: 5px;" class='form_last_3' type="text" name='email' id='email' value="<?php if(!empty($email)
          ){ echo h($email); } ?>">
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
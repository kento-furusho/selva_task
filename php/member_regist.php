<?php

require_once('validate.php');

$prefectures = array(
  1 => '北海道',
  2 => '青森県',
  3 => '岩手県',
  4 => '宮城県',
  5 => '秋田県',
  6 => '山形県',
  7 => '福島県',
  8 => '茨城県',
  9 => '栃木県',
  10 => '群馬県',
  11 => '埼玉県',
  12 => '千葉県',
  13 => '東京都',
  14 => '神奈川県',
  15 => '山梨県',
  16 => '長野県',
  17 => '新潟県',
  18 => '富山県',
  19 => '石川県',
  20 => '福井県',
  21 => '岐阜県',
  22 => '静岡県',
  23 => '愛知県',
  24 => '三重県',
  25 => '滋賀県',
  26 => '京都府',
  27 => '大阪府',
  28 => '兵庫県',
  29 => '奈良県',
  30 => '和歌山県',
  31 => '鳥取県',
  32 => '島根県',
  33 => '岡山県',
  34 => '広島県',
  35 => '山口県',
  36 => '徳島県',
  37 => '香川県',
  38 => '愛媛県',
  39 => '高知県',
  40 => '福岡県',
  41 => '佐賀県',
  42 => '長崎県',
  43 => '熊本県',
  44 => '大分県',
  45 => '宮崎県',
  46 => '鹿児島県',
  47 => '沖縄県'
);

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
    }
  }

    // エラー後、入力保持用
    $name_sei = $_SESSION['name_sei'];
    $name_mei = $_SESSION['name_mei'];
    $gender = $_SESSION['gender'];
    $pref_name = $_SESSION['pref_name'];
    $address = $_SESSION['address'];
    $password = $_SESSION['password'];
    $re_password = $_SESSION['re_password'];
    $email = $_SESSION['email'];
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
    <?php echo $err_mes ?>
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
          <input class='input_name' type="text" name='name_sei' id='name_sei' value="<?php if(!empty($name_sei) ){ echo $name_sei; } ?>">
          <label for="name_mei">名</label>
          <input class='input_name' type="text" name='name_mei' id='name_mei' value="<?php if(!empty($name_mei) ){ echo $name_mei; } ?>">
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
          <input style="width: 237px;" type="text" name='address' id='name_sei' value="<?php if(!empty($address) ){ echo $address; } ?>">
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
          <input style="margin-left: 5px;" class='form_last_3' type="text" name='email' id='email' value="<?php if($email 
          ){ echo $email; } ?>">
        </p>
        <div class='btn-container'>
          <a>
            <input class="btn" type="submit" value="確認画面へ">
          </a>
        </div>
      </form>
  </div>
</body>
</html>
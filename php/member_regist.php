<?php
  session_start();
  if(!empty($_POST)){
    $family_name = $_POST['family_name'];
    $given_name = $_POST['given_name'];
    $gender = $_POST['gender'];
    $pref_name = $_POST['pref_name'];
    $last_address = $_POST['last_address'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];
    $mail = $_POST['mail'];

    var_dump($_POST['family_name']);

    function validation($data) {
      $err_msg = array();
      if (empty($data['family_name']) || empty($data['given_name'])) {
        $err_msg[] = '氏名を入力してください';
      }
      if (mb_strlen($data['family_name']) > 20) {
        $err_msg[] = '苗字は20文字以内で入力してください';
      }
      if (mb_strlen($data['given_name']) > 20) {
        $err_msg[] = '名前は20文字以内で入力してください';
      }
      return $err_msg;
    }

    global $err_messages;
    $err_messages = validation($_POST);

    if (empty($err_messages)) {
      $_SESSION['family_name'] = $family_name;
      $_SESSION['given_name'] = $given_name;
      $_SESSION['gender'] = $gender;
      $_SESSION['pref_name'] = $pref_name;
      $_SESSION['last_address'] = $last_address;
      $_SESSION['password'] = $password;
      $_SESSION['re_password'] = $re_password;
      $_SESSION['mail'] = $mail;
      header("Location:member_regist_confirm.php");
    }
  }
    
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
        <div>
          <?php
            if(!empty($err_messages)) {
              foreach ($err_messages as $err_msg) {
                echo $err_msg.'<br/>';
              }
            }
          ?>
        </div>
        <p>氏名
          <label for="family_name">姓</label>
          <input class='input_name' type="text" name='family_name' id='family_name' value="<?php if(!empty($family_name) ){ echo $family_name; } ?>">
          <label for="given_name">名</label>
          <input class='input_name' type="text" name='given_name' id='given_name' value="<?php if(!empty($_POST['given_name']) ){ echo $_POST['given_name']; } ?>">
        </p>
        <p>性別
          <input style="margin-left: 20px;" type="radio" name='gender' value='男性' 
          <?php if (isset($_SESSION['gender']) && $_SESSION['gender'] == "男性") echo 'checked'; ?>>男性
          <input type="radio" name='gender' value="女性" 
          <?php if (isset($_SESSION['gender']) && $_SESSION['gender'] == "女性") echo 'checked'; ?>>女性
        </p>
        <p>住所
          <label>都道府県</label>
          <select name="pref_name">
            <option value="0">選択してください</option>
            <?php foreach($prefectures as $prefecture): ?>
                <option value="<?php echo $prefecture ?>"
                <?php if(!empty($_SESSION['pref_name']) && $prefecture === $_SESSION['pref_name']) echo 'selected'; ?>><?php echo $prefecture ?>
            <?php endforeach; ?>
          </select>
        </p>
        <p style="margin-left: 38px;">
          <label for="last_address" class="address_label">それ以降の住所</label>
          <input style="width: 237px;" type="text" name='last_address' id='family_name' value="<?php if(!empty($_SESSION['last_address']) ){ echo $_SESSION['last_address']; } ?>">
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
          <label for="mail">メールアドレス</label>
          <input style="margin-left: 5px;" class='form_last_3' type="text" name='mail' id='mail' value="<?php if(!empty($_SESSION['mail']) ){ echo $_SESSION['mail']; } ?>">
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
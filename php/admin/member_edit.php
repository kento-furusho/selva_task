<?php
require_once('../validate.php');
require_once('../function.php');
session_start();
// 会員編集
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    // 個々の情報取得
    if(empty($edit_err_msg)) {
      try{
      $pdo = db_connect();
      $prepare = $pdo->prepare("SELECT * FROM members WHERE id = :id");
      $prepare->bindValue(':id', $id, PDO::PARAM_INT);
      if($prepare->execute()) {
        $member = $prepare->fetch();
      }
      $pdo = null;
    } catch(PDOException $e) {
      $edit_err_msg[] = $e->getMessage();
    };
  }

  if(!empty($_POST['submit'])){
    $_SESSION['name_sei'] = $_POST['name_sei'];
    $_SESSION['name_mei'] = $_POST['name_mei'];
    $_SESSION['gender'] = $_POST['gender'];
    $_SESSION['pref_name'] = $_POST['pref_name'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['re_password'] = $_POST['re_password'];
    $_SESSION['email'] = $_POST['email'];

    // バリデーション
    $edit_err_msg = edit_validation($_POST, $prefectures);

    if (empty($edit_err_msg)) {
      header("Location:member_edit_confirm.php?id=$id");
      exit;
    }
  }
} else {
  // 会員登録
  if(!empty($_POST['submit'])){
    $_SESSION['name_sei'] = $_POST['name_sei'];
    $_SESSION['name_mei'] = $_POST['name_mei'];
    $_SESSION['gender'] = $_POST['gender'];
    $_SESSION['pref_name'] = $_POST['pref_name'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['re_password'] = $_POST['re_password'];
    $_SESSION['email'] = $_POST['email'];

    // バリデーション
    $edit_err_msg = validation($_POST, $prefectures);

    if (empty($edit_err_msg)) {
      header("Location:member_edit_confirm.php");
      exit;
    }
  }
}

  // エラー後、入力保持用
  if(!empty($_SESSION['name_sei']) ||
    !empty($_SESSION['name_mei']) ||
    !empty($_SESSION['gender']) ||
    !empty($_SESSION['pref_name']) ||
    !empty($_SESSION['address']) ||
    !empty($_SESSION['password']) ||
    !empty($_SESSION['re_password']) ||
    !empty($_SESSION['email'])
    ){
    $name_sei = $_SESSION['name_sei'];
    $name_mei = $_SESSION['name_mei'];
    $gender = $_SESSION['gender'];
    $pref_name = $_SESSION['pref_name'];
    $address = $_SESSION['address'];
    $password = $_SESSION['password'];
    $re_password = $_SESSION['re_password'];
    $email = $_SESSION['email'];
  }

  $_SESSION['name_sei'] = '';
  $_SESSION['name_mei'] = '';
  $_SESSION['gender'] = '';
  $_SESSION['pref_name'] = '';
  $_SESSION['address'] = '';
  $_SESSION['password'] = '';
  $_SESSION['re_password'] = '';
  $_SESSION['email'] = '';

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <title>会員編集ページ</title>
</head>
<body style="background-color:#E2EDF6;">
<header class="admin_header">
    <div class="header-left">
      <h3 style="line-height:60px; margin-left:20px;">
        <?php
          if(empty($member)) {
            echo '会員登録';
          } else {
            echo '会員編集';
          }
        ?>
      </h3>
    </div>
    <div class="header-right">
      <a class="header-btn right-side-btn" href="members.php">一覧へ戻る</a>
    </div>
  </header>
  <div>
    <form class='forms' method="post" action="<?php if(empty($member)){echo 'member_edit.php';}else{echo 'member_edit.php?id='.$id;}?>">
        <div class="err_msg" style="text-align:center; margin:15px auto;">
          <?php
            if(!empty($edit_err_msg)) {
              foreach ($edit_err_msg as $err_msg) {
                echo '※'.$err_msg.'<br/>';
              }
            }
          ?>
        </div>
        <p>ID
          <?php if(!empty($member)){
              echo h($member['id']);
            } else {
              echo '登録後に自動採番';
            }
          ?>
        </p>
        <p>氏名
          <label for="name_sei">姓</label>
          <input class='input_name' type="text" name='name_sei' id='name_sei' value="<?php if(!empty($name_sei)){ echo h($name_sei);}elseif(empty($edit_err_msg) && !empty($member)){echo $member['name_sei'];} ?>">
          <label for="name_mei">名</label>
          <input class='input_name' type="text" name='name_mei' id='name_mei' value="<?php if(!empty($name_mei)){ echo h($name_mei);}elseif(empty($edit_err_msg) && !empty($member)){echo $member['name_mei'];} ?>">
        </p>
        <p>性別
          <input style="margin-left: 20px;" type="radio" name='gender' value='1'
          <?php if(isset($gender) && $gender == '1'){echo 'checked';}elseif(!empty($member) && $member['gender']==1 && empty($edit_err_msg)){echo 'checked';} ?>>男性
          <input type="radio" name='gender' value='2'
          <?php if(isset($gender) && $gender == '2'){echo 'checked';}elseif(!empty($member) && $member['gender']==2 && empty($edit_err_msg)){echo 'checked';} ?>>女性
        </p>
        <p>住所
          <label>都道府県</label>
          <select name="pref_name">
            <option value="0">選択してください</option>
            <?php foreach($prefectures as $prefecture): ?>
                <option value="<?php echo $prefecture ?>"
                <?php if(!empty($pref_name) && $prefecture === $pref_name){echo 'selected';}elseif(!empty($member) && $member['pref_name'] === $prefecture && empty($edit_err_msg)){echo 'selected';} ?>>
                <?php echo $prefecture ?>
            <?php endforeach; ?>
          </select>
        </p>
        <p style="margin-left: 38px;">
          <label for="address" class="address_label">それ以降の住所</label>
          <input style="width: 237px;" type="text" name='address' id='name_sei' value="<?php if(!empty($address)){echo h($address);}elseif(empty($edit_err_msg) && !empty($member)){echo h($member['address']);} ?>">
        </p>
        <p>
          <label for="password">パスワード</label>
          <input style="margin-left: 37px;" class='form_last_3' type="password" name='password' id='password'>
        </p>
        <p>
          <label for="re_password">パスワード確認</label>
          <input style="margin-left: 5px;" class='form_last_3' type="password" name='re_password' id='re_password' >
        </p>
        <p>
          <label for="email">メールアドレス</label>
          <input style="margin-left: 5px;" class='form_last_3' type="text" name='email' id='email' value="<?php if(!empty($email)
          ){echo h($email);}elseif(empty($edit_err_msg) && !empty($member)){echo $member['email'];} ?>">
        </p>
        <div class='btn-container'>
          <a>
            <input class="back_btn" name="submit" type="submit" value="確認画面へ">
          </a>
        </div>
      </form>
  </div>
</body>
</html>
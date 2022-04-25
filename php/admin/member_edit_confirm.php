<?php
require_once('../function.php');
date_default_timezone_set('Asia/Tokyo');
ini_set('display_errors', 'on');
session_start();
$db_err_msg = null;
$pdo = null;
$stmt = null;
$res = null;
// 日時取得
if(isset($_GET['id'])) {
  $id = $_GET['id'];
}
$current_date = date("Y-m-d H:i:s");
if(!isset($_GET['id'])) {
  if(!empty($_POST)) {
    // 接続
    try {
        $pdo = db_connect();
        // sql作成
        $prepare = $pdo->prepare(
          "INSERT INTO members (name_sei, name_mei, gender, pref_name, address, password, email, created_at, updated_at)
        VALUES (:name_sei, :name_mei, :gender, :pref_name, :address, :password, :email, :created_at, :updated_at)"
      );
        // 値のセット
        $prepare->bindParam( ':name_sei', $_POST['name_sei'], PDO::PARAM_STR);
        $prepare->bindParam( ':name_mei', $_POST['name_mei'], PDO::PARAM_STR);
        $prepare->bindValue( ':gender', $_POST['gender'], PDO::PARAM_INT);
        $prepare->bindParam( ':pref_name', $_POST['pref_name'], PDO::PARAM_STR);
        $prepare->bindParam( ':address', $_POST['address'], PDO::PARAM_STR);
        $prepare->bindParam( ':password', $_POST['password'], PDO::PARAM_STR);
        $prepare->bindParam( ':email', $_POST['email'], PDO::PARAM_STR);
        $prepare->bindParam( ':created_at', $current_date, PDO::PARAM_STR);
        $prepare->bindParam( ':updated_at', $current_date, PDO::PARAM_STR);
        // 実行
        $res = $prepare->execute();

        $pdo = null;
        header('location:members.php');
        exit;
    } catch(PDOException $e) {
        $err_msg[] = $e->getMessage();
    }
    if($res) {
        header("Location:members.php");
      } else {
        $db_err_msg[] = 'メールアドレスが既に存在しています。';
      }
    }
} else {
    if(!empty($_POST)) {
      try {
        $id = $_GET['id'];
        $pdo = db_connect();
        // sql作成
        $prepare = $pdo->prepare(
          "UPDATE members SET name_sei = :name_sei, name_mei = :name_mei, gender = :gender, pref_name = :pref_name, address = :address, email = :email, updated_at = :updated_at WHERE id = $id"
        );
        // 値のセット
        $prepare->bindParam( ':name_sei', $_POST['name_sei'], PDO::PARAM_STR);
        $prepare->bindParam( ':name_mei', $_POST['name_mei'], PDO::PARAM_STR);
        $prepare->bindValue( ':gender', $_POST['gender'], PDO::PARAM_INT);
        $prepare->bindParam( ':pref_name', $_POST['pref_name'], PDO::PARAM_STR);
        $prepare->bindParam( ':address', $_POST['address'], PDO::PARAM_STR);
        $prepare->bindParam( ':email', $_POST['email'], PDO::PARAM_STR);
        $prepare->bindParam( ':updated_at', $current_date, PDO::PARAM_STR);
        // 実行
        $res = $prepare->execute();

        // パスワードのみ個別で
        if(!empty($_POST['password'])) {
          $prepare = $pdo->prepare(
            "UPDATE members SET password = :password WHERE id = $id"
          );
          $prepare->bindParam( ':password', $_POST['password'], PDO::PARAM_STR);
          $pass_res = $prepare->execute();
        }

        $pdo = null;
        header('location:members.php');
        exit;
      } catch(PDOException $e) {
        $err_msg[] = $e->getMessage();
      }
      if($res && $pass_res) {
        header("Location:members.php");
      } else {
        $db_err_msg[] = 'メールアドレスが既に存在しています。';
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <title>会員登録・編集確認画面</title>
</head>
<body style="background-color:#E2EDF6;">
  <header class="admin_header">
    <div class="header-left">
      <h3 style="line-height:60px; margin-left:20px;">
        <?php
          if(empty($id)) {
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
      <div class="err_msg" style="text-align:center; margin:15px auto;">
        <?php
          if(!empty($db_err_msg)) {
            foreach ($db_err_msg as $err_msg) {
              echo '※'.$err_msg.'<br/>';
            }
          }
        ?>
      </div>
      <form method="post" action="<?php if(empty($id)){echo 'member_edit_confirm.php';}else{echo 'member_edit_confirm.php?id='.$id;}?>">
        <div class='content'>
          <input type="hidden" name="name_sei" value="<?php echo $_SESSION['name_sei']?>">
          <input type="hidden" name="name_mei" value="<?php echo $_SESSION['name_mei']?>">
          <input type="hidden" name="gender" value="<?php echo $_SESSION['gender']?>">
          <input type="hidden" name="pref_name" value="<?php echo $_SESSION['pref_name']?>">
          <input type="hidden" name="address" value="<?php echo $_SESSION['address']?>">
          <input type="hidden" name="password" value="<?php echo $_SESSION['password']?>">
          <input type="hidden" name="email" value="<?php echo $_SESSION['email']?>">
          <p>ID
          <?php if(!empty($id)){
              echo h($id);
            } else {
              echo '登録後に自動採番';
            }
          ?>
        </p>
          <p>氏名
            <?php echo h($_SESSION['name_sei'])?>
            <?php echo h($_SESSION['name_mei'])?>
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
            <?php echo h($_SESSION['pref_name'])?>
            <?php echo h($_SESSION['address'])?>
          </p>
          <p>パスワード
            <?php echo 'セキュリティのため非表示'?>
          </p>
          <p>メールアドレス
            <span style='color: #6495ed;'>
              <?php echo h($_SESSION['email'])?>
            </span>
          </p>
        </div>
        <div class='btn-container'>
          <a>
            <input class="back_btn" type="submit" value=<?php if(empty($id)){echo '登録完了';} else{echo '編集完了';}?>>
          </a>
        </div>
        <div class='btn-container'>
          <a href="<?php if(empty($id)){echo 'member_edit.php';}else{echo 'member_edit.php?id='.$id;}?>">
            <input class="back_btn" type="button" value="前に戻る">
          </a>
        </div>
      </form>
  </div>
</body>
</html>
<?php
// 開発者モード
ini_set('display_errors', 'on');
// ログイン済みなら遷移する(トップペーじ作ってから実装)
if (!empty($_SESSION['loggedin'])) {
  header("Location:index.php");
  exit();
}
session_start();
// エラー文
$login_err_msg = array();

if (isset($_POST['login'])) {
  // 一旦セッション入れる
  $_SESSION['email'] = $_POST['email'];
  // 空白時エラー
  if(empty($_POST['email']) && empty($_POST['password'])) {
    $login_err_msg[] ='メールアドレスとパスワードを入力してください';
  }
    elseif (empty($_POST['email'])){
    $login_err_msg[] ='メールアドレスを入力してください';
  } elseif (empty($_POST['password'])) {
    $login_err_msg[] ='パスワードを入力してください';
  }
  // 接続
  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $email = $_POST['email'];
    try {
      $option = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
      );
        $pdo = new PDO('mysql:charset=utf8mb4;dbname=selva_task;host=localhost', 'root', 'pass7610', $option);
        // sqlインジェクション対策
        $prepare = $pdo->prepare("SELECT * FROM members WHERE email = ?");
        $prepare->execute(array($email));

        $password = $_POST['password'];
        // // エラー確認
        // $row = $prepare->fetch(PDO::FETCH_ASSOC);

        if ($result = $prepare->fetch(PDO::FETCH_ASSOC)) {
          // password合致かつ、削除されてない
          if($password === $result['password'] && empty($result['deleted_at'])) {
              // セキュリティ向上のためのsession更新
              session_regenerate_id(true);

              $id = $result['id'];
              $prepare = $pdo->query("SELECT * FROM members WHERE id = $id");
              foreach ($prepare as $result) {
                $result['id'];
                $result['name_sei'];
                $result['name_mei'];
              }
              // トップページ用の情報を格納
              $_SESSION['member_id'] = $result['id'];
              $_SESSION['name_sei'] = $result['name_sei'];
              $_SESSION['name_mei'] = $result['name_mei'];
              $_SESSION['loggedin'] = true;
              header("Location:index.php");
              exit();
            } else {
              $login_err_msg[] ='IDもしくはパスワードが間違っています';
            }
          } else {
            $login_err_msg[] ='IDもしくはパスワードが間違っています';
          }
      } catch (PDOException $e) {
        $login_err_msg[] = $e->getMessage();
    }
  }
}
// エラー後、入力保持用
if(!empty($_SESSION['email'])) {
  $email = $_SESSION['email'];
}

$_SESSION = array();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>ログイン画面</title>
</head>
<body>
  <div class="container">
    <form class='login-forms' method="post" action="login.php">
        <h2>ログイン</h2>
        <p>
          <label for="email">メールアドレス(ID)</label>
          <input class='login_form_email' type="text" name='email' id='email' value="<?php if(!empty($email)
          ){ echo $email; } ?>">
        </p>
        <p>
          <label for="password">パスワード</label>
          <input class='login_form_pass' type="password" name='password' id='password'>
        </p>
        <div class="err_msg">
          <?php
              if(!empty($login_err_msg)) {
                foreach ($login_err_msg as $err_msg) {
                  echo '※'.$err_msg.'<br/>';
                }
              }
            ?>
        </div>
        <div class='btn-container login-btn'>
          <a>
            <input class="btn" name="login" type="submit" value="ログイン">
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
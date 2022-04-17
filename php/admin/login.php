<?php
// 開発者モード
ini_set('display_errors', 'on');
require_once('../function.php');
session_start();
// エラー文
$admin_err_msg = array();

if (isset($_POST['admin_login'])) {
  // 一旦セッション入れる
  $_SESSION['login_id'] = $_POST['login_id'];
  // バリデーション
    if (empty($_POST['login_id'])){
      $admin_err_msg[] ='ログインIDを入力してください';
    } elseif(!preg_match('/\A[a-z\d]{7,10}+\z/i', $_POST['login_id'])){
      $admin_err_msg[] = 'ログインIDは7~10文字の半角英数字で入力してください';
    }
    if (empty($_POST['password'])) {
      $admin_err_msg[] ='パスワードを入力してください';
    } elseif(!preg_match('/\A[a-z\d]{8,20}+\z/i', $_POST['password'])){
      $admin_err_msg[] = 'パスワードは8~20文字の半角英数字で入力してください';
    }
  // 接続
  if (!empty($_POST['login_id']) && !empty($_POST['password']) && empty($admin_err_msg)) {
    $login_id = $_POST['login_id'];
    try {
        $pdo = db_connect();
        // sqlインジェクション対策
        $prepare = $pdo->prepare("SELECT * FROM administers WHERE login_id = ?");
        $prepare->execute(array($login_id));

        $password = $_POST['password'];
        // // エラー確認
        // $row = $prepare->fetch(PDO::FETCH_ASSOC);

        if ($result = $prepare->fetch(PDO::FETCH_ASSOC)) {
          // password合致かつ、削除されてない
          if($password === $result['password'] && empty($result['deleted_at'])) {
              // セキュリティ向上のためのsession更新
              session_regenerate_id(true);

              $id = $result['id'];
              $prepare = $pdo->query("SELECT * FROM administers WHERE id = $id");
              foreach ($prepare as $result) {
                $result['id'];
                $result['name'];
              }
              // トップページ用の情報を格納
              $_SESSION['admin_id'] = $result['id'];
              $_SESSION['name'] = $result['name'];
              $_SESSION['admin_loggedin'] = true;
              header("Location:index.php");
              exit();
            } else {
              $admin_err_msg[] ='IDもしくはパスワードが間違っています';
            }
          } else {
            $admin_err_msg[] ='IDもしくはパスワードが間違っています';
          }
      } catch (PDOException $e) {
        $admin_err_msg[] = $e->getMessage();
    }
  }
}
// エラー後、入力保持用
if(!empty($_SESSION['login_id'])) {
  $login_id = $_SESSION['login_id'];
}

$_SESSION = array();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <title>ログインフォーム</title>
</head>
<body style="background-color:#E2EDF6;">
  <header class="admin_header">
  </header>
  <main>
    <form class='admin-forms' method="post" action="login.php">
        <h2>管理画面</h2>
        <div></div>
        <p>
          <label for="login_id">ログインID</label>
          <input class='admin_form_email' type="text" name='login_id' id='login_id' value="<?php if(!empty($login_id)
          ){ echo $login_id; } ?>">
        </p>
        <p>
          <label for="password">パスワード</label>
          <input class='admin_form_pass' type="password" name='password' id='password'>
        </p>
        <div class="err_msg">
          <?php
              if(!empty($admin_err_msg)) {
                foreach ($admin_err_msg as $err_msg) {
                  echo '※'.$err_msg.'<br/>';
                }
              }
            ?>
        </div>
        <div class='btn-container login-btn'>
          <a>
            <input class="btn" name="admin_login" type="submit" value="ログイン">
          </a>
        </div>
      </form>
  </main>
</body>
</html>
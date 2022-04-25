<?php
require_once('../function.php');
session_start();
// 会員詳細
if(isset($_GET['id'])) {
  $id = $_GET['id'];
  // 個々の情報取得
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <title>会員詳細ページ</title>
</head>
<body style="background-color:#E2EDF6;">
<header class="admin_header">
    <div class="header-left">
      <h3 style="line-height:60px; margin-left:20px;">
        会員詳細
      </h3>
    </div>
    <div class="header-right">
      <a class="header-btn right-side-btn" href="members.php">一覧へ戻る</a>
    </div>
</header>
  <div class="detail_content">
    <p>ID
      <?php echo h($member['id'])?>
    </p>
    <p>氏名
      <?php echo h($member['name_sei'])?>
      <?php echo h($member['name_mei'])?>
    </p>
    <p>性別
      <?php
        if($member['gender'] === '1') {
          echo "男性";
        } elseif ($member['gender'] === '2') {
          echo "女性";
        }
      ?>
    </p>
    <p>住所
      <?php echo h($member['pref_name'])?>
      <?php echo h($member['address'])?>
    </p>
    <p>パスワード
      <?php echo 'セキュリティのため非表示'?>
    </p>
    <p>メールアドレス
      <span style='color: #6495ed;'>
        <?php echo h($member['email'])?>
      </span>
    </p>
    <div style='text-align:center;'>
      <div style='display:inline-block;' class='btn-container'>
        <a style="text-decoration:none; padding:12px 34px; margin-right:3px;" class="back_btn" href=<?php echo 'member_edit.php?id='.$member['id']?>>
          編集
        </a>
      </div>
      <div style='display:inline-block;' class='btn-container'>
        <a style="text-decoration:none; padding:12px 34px; margin-left:3px;" class="back_btn" href=<?php echo 'member_delete.php?id='.$member['id']?>>
          削除
        </a>
      </div>
    </div>
  </div>
</body>
</html>
<?php
require_once('function.php');
ini_set('display_errors', 'on');
require_once('function.php');
session_start();
// スレッドのヘッダー読み込み
include('_header_thread.php');
$thread_err_msg = array();

////// 全件取得 ///////
if(empty($_POST['key'])) {
  try{
    // 接続
    $pdo = db_connect();
    // sql作成
    $prepare = $pdo->prepare("SELECT id, title, created_at FROM threads ORDER BY created_at DESC");
    if($prepare->execute()) {
      while($row = $prepare->fetch()) {
        $rows[] = $row;
      }
    } else {
      $thread_err_msg[] = '接続失敗';
    }
    // 接続解除
    $pdo = null;
  } catch(PDOException $e) {
      $thread_err_msg[] = $e->getMessage();
  }
}
//////// 検索 ////////
if(!empty($_POST['key'])) {
  try {
    // 接続
    $pdo = db_connect();
    // sql作成
    $prepare = $pdo->prepare("SELECT id, title, content, created_at FROM threads WHERE title LIKE (:title) OR content LIKE (:content) ORDER BY created_at DESC");
    if($prepare) {
      $key = $_POST['key'];
      $like_key = "%".$key."%";
      $prepare->bindParam(':title', $like_key, PDO::PARAM_STR);
      $prepare->bindParam(':content', $like_key, PDO::PARAM_STR);

      if($prepare->execute()) {
        while($row = $prepare->fetch()) {
          $rows[] = $row;
        }
      } else {
        $thread_err_msg[] = '検索失敗';
      }
      // 接続解除
      $pdo = null;
    }
  } catch(PDOException $e) {
    $thread_err_msg[] = $e->getMessage();
  }
}
?>
<!-- ヘッダー読み込み -->
<div class="threads">
  <!-- 一応エラー表示 -->
  <div class="err_msg">
    <?php
      if(!empty($thread_err_msg)) {
        foreach ($thread_err_msg as $err_msg) {
          echo '※'.$err_msg.'<br/>';
        }
      }
    ?>
  </div>
  <!-- 検索欄 -->
  <form class="search_form" action="thread.php" method="post">
    <input class="search_input" type="text" name="key">
    <a><input class="search_btn" type="submit" value="スレッド検索"></a>
  </form>
  <!-- 検索結果表示 -->
  <div>
    <table class="search_results">
      <?php foreach($rows as $row) :?>
        <tr class="search_result" height=35px>
            <td width=50px>ID:<?php h($row['id'])?></td>
            <td width=200px>
              <a style="text-decoration:none;"href="thread_detail.php?id=<?php echo $row['id']?>&page=1"><?php h($row['title'])?></a>
            </td>
            <td><?php echo date('Y.m.d H:i', strtotime($row['created_at']))?></td>
        </tr>
        <?php endforeach?>
      </table>
    </div>
  </div>
  <div class='btn-container'>
    <a href="index.php">
      <input class="back_btn" type="button" value="トップに戻る">
    </a>
  </div>
</body>
</html>
<?php
// 開発者モード
ini_set('display_errors', 'on');
require_once('../function.php');
session_start();
if(empty($_SESSION['admin_loggedin'])) {
  header('location:login.php');
  exit;
}
$member_err_msg = array();
////////// メンバー全件取得 //////////
// 現在のページ数
if(isset($_GET['page'])) {
  $current_page = (int)$_GET['page'];
} else {
  $current_page = 1;
}
// スタートページ取得
if($current_page > 1) {
  $start = ($current_page * 10) -10;
} else {
  $start = 0;
}
// 検索条件保持用session
if(!empty($_POST['search_id'])) {
  $_SESSION['search_id'] = $_POST['search_id'];
} elseif(!isset($_GET['transition']) && !isset($_POST['asc']) &&
!isset($_POST['desc'])) {
  $_SESSION['search_id'] = '';
}
if(!empty($_POST['gender_man']) && !empty($_POST['gender_woman'])) {
  $_SESSION['gender_man'] = $_POST['gender_man'];
  $_SESSION['gender_woman'] = $_POST['gender_woman'];
} elseif (!empty($_POST['gender_man'])) {
  $_SESSION['gender_man'] = $_POST['gender_man'];
  $_SESSION['gender_woman'] = '';
} elseif (!empty($_POST['gender_woman'])) {
  $_SESSION['gender_woman'] = $_POST['gender_woman'];
  $_SESSION['gender_man'] = '';
} elseif(!isset($_GET['transition']) && !isset($_POST['asc']) &&
!isset($_POST['desc'])) {
  $_SESSION['gender_woman'] = '';
  $_SESSION['gender_man'] = '';
}
if(!empty($_POST['pref_name'])) {
  $_SESSION['pref_name'] = $_POST['pref_name'];
} elseif(!isset($_GET['transition']) && !isset($_POST['asc']) &&
!isset($_POST['desc'])) {
  $_SESSION['pref_name'] = '';
}
if(!empty($_POST['free_word'])) {
  $_SESSION['free_word'] = $_POST['free_word'];
} elseif(!isset($_GET['transition']) && !isset($_POST['asc']) &&
!isset($_POST['desc'])) {
  $_SESSION['free_word'] = '';
}
// 検索条件も昇降ボタンも押されてない時session消去
if (empty($_POST['search_id']) &&
!isset($_POST['gender_man']) &&
!isset($_POST['gender_woman']) &&
empty($_POST['pref_name']) &&
empty($_POST['free_word']) &&
!isset($_POST['asc']) &&
!isset($_POST['desc']) &&
// 検索後のページネーション用
!isset($_GET['transition'])) {
  $_SESSION['search_id'] = '';
  $_SESSION['gender_man'] = '';
  $_SESSION['gender_woman'] = '';
  $_SESSION['pref_name'] = '';
  $_SESSION['free_word'] = '';
}
/////// 全件取得（10件ずつ） ///////
if(empty($_SESSION['search_id']) &&
empty($_SESSION['gender_man']) &&
empty($_SESSION['gender_woman']) &&
empty($_SESSION['pref_name']) &&
empty($_SESSION['free_word'])
) {
  try {
    // 接続
    $pdo = db_connect();
    // 昇順降順
    if(isset($_POST['asc'])) {
      $prepare = $pdo->prepare("SELECT id, name_sei, name_mei, gender, pref_name, address, created_at FROM members ORDER BY id asc LIMIT $start, 10");
    } elseif (isset($_POST['desc']) || empty($_POST['asc'])) {
      $prepare = $pdo->prepare("SELECT id, name_sei, name_mei, gender, pref_name, address, created_at FROM members ORDER BY id desc LIMIT $start, 10");
    }
    if($prepare->execute()) {
      while($member = $prepare->fetch()) {
        $members[] = $member;
      }
    } else {
        $member_err_msg[] = '接続失敗';
    }
    $count_member = $prepare->rowCount();
    // // 総ページ数
    $page_num = (int)ceil($count_member / 10);
    // 接続解除
    $pdo = null;
  } catch(PDOException $e) {
      $member_err_msg[] = $e->getMessage();
  };
  // 総メンバー数取得
  $pdo = db_connect();
  $res = $pdo->query("SELECT * FROM members");
  $count_member = $res->rowCount();
  // 総ページ数
  $page_num = (int)ceil($count_member / 10);
} else {
  ///////// 検索表示 ////////////
  try {
    // 10件ずつ
    // 接続
    $pdo = db_connect();
    // sql作成、条件適宜追加
    $sql = "SELECT * FROM members WHERE 1";
    // ID
    if(!empty($_SESSION['search_id'])) {
      $sql .= " AND id = :id";
    }
    // 性別
    if(!empty($_SESSION['gender_man'] && !empty($_SESSION['gender_woman']))) {
      $sql .= " AND (gender = 1 OR gender = 2)";
    } elseif(!empty($_SESSION['gender_man'])) {
      $sql .= " AND gender = 1";
    } elseif(!empty($_SESSION['gender_woman'])) {
      $sql .= " AND gender = 2";
    }
    // 都道府県
    if(!empty($_SESSION['pref_name'])) {
      $sql .= " AND pref_name = :pref_name";
    }
    // フリーワード
    if(!empty($_SESSION['free_word'])) {
      $sql .= " AND (name_sei like (:name_sei)
                OR name_mei like (:name_mei)
                OR email like (:email))";
    }
    // 昇降、１０件
    if(isset($_POST['asc'])) {
      $sql .= " ORDER BY id asc LIMIT $start, 10";
    } elseif (isset($_POST['desc']) || empty($_POST['asc'])) {
      $sql .= " ORDER BY id desc LIMIT $start, 10";
    }
    // prepare
    $prepare = $pdo->prepare($sql);

    // 値バインド
    if(!empty($_SESSION['search_id'])) {
      $id = (int)$_SESSION['search_id'];
      $prepare->bindValue(':id', $id, PDO::PARAM_INT);
    }
    if(!empty($_SESSION['pref_name'])) {
      $pref_name = $_SESSION['pref_name'];
      $prepare->bindParam(':pref_name', $pref_name, PDO::PARAM_STR);
    }
    if(!empty($_SESSION['free_word'])) {
      $free_word = $_SESSION['free_word'];
      $like_word = '%'.$free_word.'%';
      $prepare->bindParam(':name_sei', $like_word, PDO::PARAM_STR);
      $prepare->bindParam(':name_mei', $like_word, PDO::PARAM_STR);
      $prepare->bindParam(':email', $like_word, PDO::PARAM_STR);
    }
    // 実行
    $prepare->execute();
    if($prepare->execute()) {
      while($member = $prepare->fetch()) {
        $members[] = $member;
      }
    }
    // 接続解除
    $pdo = null;
  } catch(PDOException $e) {
    $member_err_msg[] = $e->getMessage();
  };
  // 総メンバー数取得
  $pdo = db_connect();
  $sql_replaced = str_replace("LIMIT $start, 10", '', $sql);
  $prepare = $pdo->prepare($sql_replaced);
  // 値バインド
  if(!empty($_SESSION['search_id'])) {
    $id = (int)$_SESSION['search_id'];
    $prepare->bindValue(':id', $id, PDO::PARAM_INT);
  }
  if(!empty($_SESSION['pref_name'])) {
    $pref_name = $_SESSION['pref_name'];
    $prepare->bindParam(':pref_name', $pref_name, PDO::PARAM_STR);
  }
  if(!empty($_SESSION['free_word'])) {
    $free_word = $_SESSION['free_word'];
    $like_word = '%'.$free_word.'%';
    $prepare->bindParam(':name_sei', $like_word, PDO::PARAM_STR);
    $prepare->bindParam(':name_mei', $like_word, PDO::PARAM_STR);
    $prepare->bindParam(':email', $like_word, PDO::PARAM_STR);
  }
  $prepare->execute();
  $count_member = $prepare->rowCount();
  // 総ページ数
  $page_num = (int)ceil($count_member / 10);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <title>会員一覧ページ</title>
</head>
<body style="background-color:#E2EDF6;">
  <header class="admin_header">
    <div class="header-left">
      <h3 style="line-height:60px; margin-left:20px;">
        会員一覧
      </h3>
    </div>
    <div class="header-right">
      <a class="header-btn right-side-btn" href="index.php">トップへ戻る</a>
    </div>
  </header>
  <main>
    <!------- 検索欄 ------->
    <form action="members.php" method="post">
      <table class="search_table">
        <tr>
          <td class="search_title">
            <label class="title_txt" for="id">ID</label>
          </td>
          <td>
            <input class="search_content" type="text" name="search_id" id="id" value='<?php if(!empty($_SESSION['search_id'])){echo h($_SESSION['search_id']);}?>'>
          </td>
        </tr>
        <tr>
          <td class="search_title">
            <label class="title_txt" for="gender">性別</label>
          </td>
          <td>
            <input style="margin-left:30px;" type="checkbox" name="gender_man" id="gender" value="1" <?php if(!empty($_SESSION['gender_man'])){echo "checked";};?>>男性
            <input type="checkbox" name="gender_woman" id="gender" value="2" <?php if(!empty($_SESSION['gender_woman'])){echo "checked";};?>>女性
          </td>
        </tr>
        <tr>
          <td class="search_title">
            <label class="title_txt" for="pref_name">都道府県</label>
          </td>
          <td>
            <select  class="search_content" name="pref_name">
              <option value="0"></option>
              <?php foreach($prefectures as $prefecture): ?>
                  <option value="<?php echo $prefecture ?>"
                  <?php if(!empty($_SESSION['pref_name']) && $prefecture === $_SESSION['pref_name']) echo 'selected'; ?>><?php echo $prefecture ?>
              <?php endforeach; ?>
            </select>
          </td>
        </tr>
        <tr>
          <td class="search_title">
            <label class="title_txt" for="free_word">フリーワード</label>
          </td>
          <td>
            <input class="search_content" type="text" name="free_word" id="free_word" value='<?php if(!empty($_SESSION['free_word'])){echo h($_SESSION['free_word']);}?>'>
          </td>
        </tr>
      </table>
      <div class="btn-container">
        <input style="padding:9px 41px" class="back_btn" type="submit" name="search" value="検索する">
      </div>
    </form>
    <!------ メンバー表示テーブル ------>
    <div>
    <table class="member_table">
        <thead>
          <tr class="member_ths">
            <th class="member_th">
              ID
              <!------ 昇順降順ボタン ------>
              <form style="display:inline-block;" action="members.php?page=<?=$current_page?>" method="POST">
                <?php if(isset($_POST['asc'])):?>
                  <input type="hidden" name="desc">
                    <button class="arrow" type="submit" value="▼">▼</button>
                <?php elseif(isset($_POST['desc']) || empty($_POST['desc'])):?>
                  <input type="hidden" name="asc">
                    <button class="arrow" type="submit" value="▼">▼</button>
                <?php endif ?>
              </form>
            </th>
            <th class="member_th">氏名</th>
            <th class="member_th">性別</th>
            <th class="member_th">住所</th>
            <th>
              登録日時
              <!------ 昇順降順ボタン ------>
              <form style="display:inline-block;" action="members.php?page=<?=$current_page?>" method="POST">
                <?php if(isset($_POST['asc'])):?>
                  <input type="hidden" name="desc">
                    <button class="arrow" type="submit" value="▼">▼</button>
                <?php elseif(isset($_POST['desc']) || empty($_POST['desc'])):?>
                  <input type="hidden" name="asc">
                    <button class="arrow" type="submit" value="▼">▼</button>
                <?php endif ?>
              </form>
            </th>
          </tr>
        </thead>
        <?php if(!empty($members)):?>
          <?php foreach($members as $member) :?>
            <tbody>
              <tr class="member_tds">
                <td class="member_td"><?= $member['id']?></td>
                <td class="member_td"><?= $member['name_sei'].' '.$member['name_mei'] ?></td>
                <td class="member_td">
                  <?php
                  if($member['gender'] == 1) {
                    echo '男性';
                  } else {
                    echo '女性';
                  }
                  ?>
                </td>
                <td class="member_td"><?= $member['pref_name'].$member['address']?></td>
                <td><?= date('Y/m/d', strtotime($member['created_at']))?></td>
              </tr>
            </tbody>
          <?php endforeach?>
        <?php endif ?>
      </table>
      <!------ 前へボタン ------>
      <div class="pagination_btns">
        <?php if($current_page > 1):?>
          <a class="page_btn back_page" href="members.php?page=<?=$current_page - 1?>&transition=true">＜前へ </a>
        <?php endif ?>
            <!------ ページネーションボタン ------>
        <!-- page_numが3以上の時 -->
        <?php if($page_num >= 3):?>
          <!-- current_pageが1の時 -->
          <?php
          if($current_page == 1) {
            for($i=1; $i<4; $i++) {
              if($i==1) {
                echo "<span class='now_page_btn'>$i</span>";
              } else {
                echo "<a class='page_btn' href='members.php?page=$i&transition=true'>$i</a>";
              }
            }
          // current_pageがmaxの時
          } elseif($current_page == $page_num) {
            for($i=$current_page-2; $i<=$current_page; $i++) {
              if($i<$current_page) {
                echo "<a class='page_btn' href='members.php?page=$i&transition=true'>$i</a>";
              } else {
                echo "<span class='now_page_btn'>$i</span>";
              }
            }
          // current_pageがそれ以外の時(前後にページがある)
          } else {
            for($i=$current_page-1; $i<=$current_page+1; $i++) {
              if($i==$current_page) {
                echo "<span class='now_page_btn'>$i</span>";
              } else {
                echo "<a class='page_btn' href='members.php?page=$i&transition=true'>$i</a>";
              }
            }
          }
          ?>
        <?php else:?>
            <!-- page_numが2以下の時 -->
            <?php for($i=1; $i<$page_num+1; $i++) {
            if($i == $current_page) {
              echo "<span class='now_page_btn'>$i</span>";
            } else {
              echo "<a class='page_btn' href='members.php?page=$i&transition=true'>$i</a>";
            }
          }
          ?>
        <?php endif ?>
        <!------ 次へボタン ------>
        <?php if($current_page < $page_num):?>
          <a class="page_btn next_page" href="members.php?page=<?=$current_page + 1?>&transition=true">次へ＞</a>
        <?php endif ?>
      </div>
    </div>
  </div>
  </main>
</body>
</html>

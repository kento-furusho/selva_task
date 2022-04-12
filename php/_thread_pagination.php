<div class="thread_gray">
<!-- 前へボタン -->
  <a
    <?php if($_GET['page'] == '1'):?>
        class='back_page_gray_btn page_btn'
    <?php else: ?>
        class='back_page_btn page_btn'
      <?php endif ?>
      href='thread_detail.php?id=<?=$id?>&page=<?=($_GET['page'])-1?>'>
    ＜前へ</a>
  <!-- 次へボタン -->
    <a
      <?php if($_GET['page'] == ((string)$page_num)):?>
        class='next_page_gray_btn page_btn'
      <?php else: ?>
        class='next_page_btn page_btn'
      <?php endif ?>
      href='thread_detail.php?id=<?=$id?>&page=<?=($_GET['page'])+1?>'>
    次へ＞</a>
</div>
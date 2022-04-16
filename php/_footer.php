<footer>
  <!-- ログイン時 -->
  <?php if(!empty($_SESSION['loggedin'])) : ?>
    <div class="header-right">
      <a class="header-btn right-side-btn" href="member_withdrawal.php">退会</a>
    </div>
  <?php endif ?>
</footer>
</body>
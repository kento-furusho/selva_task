<?php
  if(!function_exists('h')) {
    function h($s) {
      echo htmlspecialchars($s, ENT_QUOTES, "UTF-8");
    }
  }
?>
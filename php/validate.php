<?php
    function validation($data, $prefectures) {
      $err_msg = array();
      // 名前
      if (empty($data['name_sei']) || empty($data['name_mei'])) {
        $err_msg[] = '氏名を入力してください';
      }
      if (mb_strlen($data['name_sei']) > 20) {
        $err_msg[] = '苗字は20文字以内で入力してください';
      }
      if (mb_strlen($data['name_mei']) > 20) {
        $err_msg[] = '名前は20文字以内で入力してください';
      }
      // 性別
      if (empty($data['gender'])) {
        $err_msg[] = '性別を選択してください';
      }
      elseif (!in_array($data['gender'], [1, 2])) {
        $err_msg[] = '性別を正しく選択してください';
      }
      // 都道府県
      if (empty($data['pref_name'])) {
        $err_msg[] = '都道府県を選択してください';
      }
      elseif (!in_array($data['pref_name'], $prefectures)) {
        $err_msg[] = '都道府県を正しく選択してください';
      }
      // 住所
      if (mb_strlen($data['address']) > 100 ) {
        $err_msg[] = '住所は100文字以内で入力してください';
      }
      // パスワード
      if (empty($data['password']) || empty($data['re_password'])) {
        $err_msg[] = 'パスワードを入力してください';
      }
      elseif(!preg_match('/\A[a-z\d]{8,20}+\z/i', $data['password']) || !preg_match('/\A[a-z\d]{8,20}+\z/i', $data['re_password'])){
        $err_msg[] = 'パスワードは8~20文字の半角英数字が使用できます';
      }
      // パスワード確認
      if ($data['password'] !== $data['re_password']) {
        $err_msg[] = 'パスワードが一致しません';
      }
      // メールアドレス
      if (mb_strlen($data['email']) > 201 ) {
        $err_msg[] = 'メールアドレスは200文字以内で入力してください';
      }
      if (empty($data['email'])) {
        $err_msg[] = 'メールアドレスを入力してください';
      }
      elseif(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/", $data['email'])){
        $err_msg[] = '正しいメールアドレスを入力してください';
      }
      return $err_msg;
    }
?>
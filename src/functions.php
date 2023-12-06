<?php
  // URL 유효성 검사
  function is_url_valid($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
  }

  // 이메일 유효성 검사
  function is_email_valid($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  }

  // error 처리 함수
  function error($msg) {
    echo "<script language='javascript'>alert('$msg'); history.back();</script>";
    exit; // 스크립트 종료
  }

  // forwarding 처리 함수
  function forward($url) {
    header("Location: $url");
    exit; // 스크립트 종료
  }

  // 링크를 만들어 주는 함수
  function dest_url($link, $page, $uid = null, $kind = null, $key = null) {
    $link .= "?page=$page";
    if ($uid) $link .= "&uid=$uid";
    if ($key) $link .= "&kind=$kind&key=$key";
    return $link;
  }
?>

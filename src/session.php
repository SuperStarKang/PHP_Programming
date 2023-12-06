<?php
session_start();

/**
 * 로그인되어 있는지 반환
 * @return bool
 */
function is_logged_in()
{
  if (!$_SESSION)
    return false;
  return !!$_SESSION["id"];
}

/**
 * 로그인되어 있다면 id와 nickname을 연상 배열 형태로, 아니라면 null을 반환 
 * @return array|null
 */
function get_auth_session()
{
  if (is_logged_in())
    return array("id" => $_SESSION["id"], "nickname" => $_SESSION["nickname"]);
  else
    return null;
}

/**
 * $is_login == true라면 $id, $nickname을 세션에 저장
 * 아니라면 null로 변경
 * @param bool $is_login
 * @param string $id
 * @param string $nickname
 * @return void
 */
function set_auth_session($is_login, $id, $nickname)
{
  if ($is_login) {
    $_SESSION["id"] = $id;
    $_SESSION["nickname"] = $nickname;
  } else {
    $_SESSION["id"] = null;
    $_SESSION["nickname"] = null;
  }
}


?>
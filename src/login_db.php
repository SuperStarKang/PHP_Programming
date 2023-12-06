<?php

include("./config.php");
include("./functions_by_limeojin.php");
include("./session.php");

$id = trim($_POST['id']);
$password = trim($_POST['password']);

if (!strlen($id) || !strlen($password)) {
  error("비어 있는 입력이 있습니다.");
}

$query = "SELECT nickname, password FROM user WHERE user_id='$id'";
$result = $con->query($query);

list($nickname, $password_origin)
  = mysqli_fetch_array($result);

if ($password == $password_origin) {
  set_auth_session(true, $id, $nickname);
  alert_and_navigate("로그인 성공!", "./home.php");
}

error("아이디나 비밀번호를 확인해주세요.");

?>
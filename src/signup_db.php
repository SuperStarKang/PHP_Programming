<?php

include("./config.php");
include("./functions_by_limeojin.php");

$id = trim($_POST['id']);
$password = trim($_POST['password']);
$password_confirm = trim($_POST['password_confirm']);
$nickname = trim($_POST['nickname']);

if (!strlen($id) || !strlen($password) || !strlen($password_confirm) || !strlen($nickname)) {
  error("비어 있는 입력이 있습니다.");
}

if ($password !== $password_confirm) {
  error("비밀번호와 비밀번호 확인 입력이 일치하지 않습니다.");
}


try {
  $query = "INSERT INTO user(user_id, nickname, password) VALUES('$id', '$nickname', '$password')";
  $result = $con->query($query);
} catch (\Throwable $th) {
  error("이미 존재하는 id입니다.");
}

alert_and_navigate("회원가입에 성공하였습니다.", "./home.php");

?>
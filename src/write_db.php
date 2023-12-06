<?php
include("config.php");
include("functions_by_limeojin.php");
include("session.php");

$user_id = get_auth_session()['id'];
$nickname = get_auth_session()['nickname'];
$board_type = isset($_GET['board_type']) ? $_GET['board_type'] : '';

$title = trim($_POST['title']);
$content = trim($_POST['content']);
$hit = 0;
$comment = 0;

if (!$title || !$content) {
	error("입력값이 부족합니다.");
	exit;
}

$time = date("Y-m-d h:i:s");

// 로그인 전 테스트

$stmt = $con->prepare("INSERT INTO Post (post_id, user_id, nickname, board_type, title, content, `time`, hit, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ississsii", $post_id, $user_id, $nickname, $board_type, $title, $content, $time, $hit, $comment);
$stmt->execute();

$con->close();
forward("./board.php?board_type=$board_type");
?>
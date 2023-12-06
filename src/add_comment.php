<?php
include("config.php");
include("functions_by_limeojin.php");
include("session.php");

$post_id = $_POST['post_id'];

if (get_auth_session() == NULL) {
	$redirect_url = get_post_url($post_id);
	alert_and_navigate("먼저 로그인해 주세요.", $redirect_url);
}


// 현재 사용자 ID (세션 등을 통해 얻어와야 함)
$nickname = get_auth_session()['nickname']; // 예시로 abc123으로 가정
// 게시글 ID (폼 등에서 얻어와야 함)
$post_id = $_POST['post_id'];
$comment_content = $_POST['comment_content'];

if (!$comment_content) {
	error("입력값이 부족합니다.");
	exit;
}
$insert_query = "INSERT INTO `Comment` (content, post_id, nickname, `time`) VALUES ('$comment_content', '$post_id', '$nickname', NOW())";
$comment_num_inc_query = "UPDATE `Post` SET `comment` = `comment` + 1 WHERE post_id = '$post_id'";

$query_update_hit = "UPDATE Post SET hit = hit - 1 WHERE post_id = ?";
$stmt_update_hit = $con->prepare($query_update_hit);
$stmt_update_hit->bind_param("i", $post_id);
$stmt_update_hit->execute();

if (mysqli_query($con, $insert_query) === TRUE && mysqli_query($con, $comment_num_inc_query) === TRUE) {
	$redirect_url = get_post_url($post_id);
	header("Location: $redirect_url");
} else {
	error("시스템 오류입니다. 다시 시도해주세요.");
}

function get_post_url($post_id)
{
	return "view_post.php?post_id=" . $post_id;
}
?>
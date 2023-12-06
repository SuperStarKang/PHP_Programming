<?php
include("./config.php");
include("./functions_by_limeojin.php");

$post_id = trim($_POST['post_id']);
$title = trim($_POST['title']);
$content = trim($_POST['content']);
$board_type = isset($_POST['board_type']) ? (int) $_POST['board_type'] : 0;

if (!$title || !$content) {
	error("입력값이 부족합니다.");
	exit;
}

$query = "UPDATE Post SET title='$title', content='$content'
			where post_id=$post_id;";

$con->query($query) or die("Error Occured!");

forward("./view_post.php?post_id=$post_id");
?>
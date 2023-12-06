<?php
	include("config.php");
	include("functions.php");
	include("session.php");

	// 현재 사용자 ID (세션 등을 통해 얻어와야 함)
	$nickname = get_auth_session()['nickname']; // 예시로 abc123으로 가정
	if ($nickname === NULL)	// 로그인 안되어있을 때
	{
		error("로그인 하세요.");
	}
	else
	{
		// 게시글 ID (폼 등에서 얻어와야 함)
		// $comment_id = $_POST['comment_id'];
		$comment_id = isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;
		$select_query = "SELECT nickname, post_id FROM `Comment` WHERE (comment_id = '$comment_id');";
		$result = mysqli_fetch_assoc(mysqli_query($con, $select_query));
		$comment_nickname = (string)$result['nickname'];
		$post_id = (int)$result['post_id'];
		if (strcmp($nickname, $comment_nickname) == 0)
		{
			$delete_query = "DELETE FROM `Comment` WHERE (`comment_id` = '$comment_id');";
			$comment_num_dec_query = "UPDATE `Post` SET comment = comment - 1 WHERE post_id = '$post_id'";
			if (mysqli_query($con, $delete_query) === TRUE && mysqli_query($con, $comment_num_dec_query) === TRUE) {
				$redirect_url = get_post_url($post_id);
				header("Location: $redirect_url");
				exit();
			}
			else 
			{
				error("시스템 오류입니다. 다시 시도해주세요.");
			}
		}
		else
			echo "로그인한 ID와 작성자가 다릅니다.<br>";
	}

	// 게시판의 URL을 반환하는 함수 (예시)
	function get_post_url($post_id) {
		return "view_post.php?post_id=".$post_id;
	}
	
?>

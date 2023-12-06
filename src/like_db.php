<?php
	include("config.php");
	include("functions_by_limeojin.php");
	include("session.php");

	$user_id = get_auth_session()['id']; // 예시로 abc123으로 가정
	if ($user_id === NULL)	// 로그인 안되어있을 때
	{
		error("로그인 하세요.");
	}
	else
	{
		// 게시글 ID (폼 등에서 얻어와야 함)
		$post_id = $_POST['post_id']; // 예시로 POST 방식 사용

		// 좋아요 버튼이 눌렸을 때
		if (isset($_POST['like_button'])) 
		{
			// 이미 좋아요를 눌렀는지 확인
			$check_query = "SELECT * FROM `Like` WHERE user_id = '$user_id' AND post_id = '$post_id'";
			$check_post_query = "SELECT * FROM `Post` WHERE post_id = '$post_id'";
			$post_result = mysqli_query($con, $check_post_query);

			$query_update_hit = "UPDATE Post SET hit = hit - 1 WHERE post_id = ?";
			$stmt_update_hit = $con->prepare($query_update_hit);
			$stmt_update_hit->bind_param("i", $post_id);
			$stmt_update_hit->execute();

			$like_result = mysqli_query($con, $check_query);
			if ($post_result->num_rows == 0)
				echo "($post_id)해당 게시글은 존재하지 않습니다.<br>";
			else if ($like_result->num_rows == 0) 
			{
				// 좋아요를 누른 적이 없다면 새로운 좋아요 추가
				$insert_query = "INSERT INTO `Like` (user_id, post_id) VALUES ('$user_id', '$post_id')";
				$like_num_inc_query = "UPDATE `Post` SET `like` = `like` + 1 WHERE post_id = '$post_id'";

				if (mysqli_query($con, $insert_query) === TRUE && mysqli_query($con, $like_num_inc_query) === TRUE) {
					$redirect_url = get_post_url($post_id);
					header("Location: $redirect_url");
				}
				else 
				{
					error("시스템 오류입니다. 다시 시도해주세요.");
				}
			} 
			else 
			{
				// 이미 좋아요를 누른 경우 취소
				$delete_query = "DELETE FROM `Like` WHERE user_id = '$user_id' AND post_id = '$post_id'";
				$like_num_dec_query = "UPDATE `Post` SET `like` = `like` - 1 WHERE post_id = '$post_id'";
				if (mysqli_query($con, $delete_query) === TRUE && mysqli_query($con, $like_num_dec_query) === TRUE) {
					$redirect_url = get_post_url($post_id);
					header("Location: $redirect_url");
				}
				else 
				{
					error("시스템 오류입니다. 다시 시도해주세요.");
				}
			}
		}
	}

	function get_post_url($post_id) {
		return "view_post.php?post_id=".$post_id;
	}
?>

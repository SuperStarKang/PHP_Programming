<?php
	include("./config.php");
	include("./functions_by_limeojin.php");
	include("./session.php");

	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		// GET으로 전달된 post_id 확인
		$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

		$query = "SELECT board_type FROM Post WHERE post_id = ?";
		$stmt = $con->prepare($query);
		$stmt->bind_param("i", $post_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$board_type = $row['board_type'];

		// 해당 post_id에 대한 삭제 쿼리 실행
		$query_delete_post = "DELETE FROM Post WHERE post_id = ?";
		$stmt_delete_post = $con->prepare($query_delete_post);
		$stmt_delete_post->bind_param("i", $post_id);

		// 좋아요, 댓글 테이블에서도 post_id 에 따라 삭제
		if ($stmt_delete_post->execute()) {
			// 성공적으로 삭제되었을 경우, 다시 해당 게시판으로 이동
			$redirect_url = get_board_url($board_type); // 함수를 통해 게시판 URL을 가져옴
			header("Location: $redirect_url");
			exit();
		}
		else {
			// 삭제 실패 시 처리
			echo "게시물 삭제에 실패했습니다.";
		}
	}
	else {
		// GET으로 요청이 아닌 경우 처리
		echo "올바르지 않은 요청입니다.";
	}

	function get_board_url($board_type) {
		return "board.php?board_type=" . $board_type;
	}
?>
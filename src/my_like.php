<?php
include("./config.php");
include("./functions_by_limeojin.php");
include("./session.php");

$user_id = get_auth_session()['id'];
// 로그인되어 있는 상태에서 접근 불가능한 페이지인 경우, 아래에 있는 주석을 해제해주세요..
// if (is_logged_in()) {
//   alert_and_navigate("이미 로그인되어 있습니다. 사이트 메인 페이지로 돌아갑니다.", "./home.php");
// }

// 로그인되어 있지 않은 상태에서 접근 불가능한 페이지인 경우, 아래에 있는 주석을 해제해주세요..
// if (!is_logged_in()) {
//   alert_and_navigate("로그인되어 있지 않습니다. 로그인 페이지로 이동합니다.", "./login.php");
// }

?>

<html>

  <head>
    <style>
    <?php include("./global.css") ?><?php include("./header.css") ?>
    /* [스타일 코드를 적으세요] */
    </style>
    <title>[여기다가 제목을 넣으세요]</title>
  </head>

  <body>
    <header>
      <div class="header_row1">
        <a href="home.php" class="header_logo">UOS-CS</a>
        <!-- TODO: action attribute를 적절한 URL로 수정 -->
        <form class="header_search" method="post" action="search_db.php">
          <select name="search_type" class="header_search_options">
            <option value="제목">제목</option>
            <option value="내용">내용</option>
            <option value="글쓴이">글쓴이</option>
            <select>
              <input name="search_word" class="header_search_text_input" placeholder="검색어를 입력해주십쇼">
              <button>검색</button>
        </form>
        <div class="header_top_right">
          <?= is_logged_in()
						? '<a href="./logout_db.php">로그아웃</a><a href="./my-profile.php">내프로필</a>'
						: '<a href="./login.php">로그인</a><a href="./signup.php">회원가입</a>';
					?>
        </div>
      </div>
      <div class="header_row2">
        <!-- TODO: 과목명과 URL 수정 -->
        <a href="board.php?board_type=a">과목 A</a>
        <a href="board.php?board_type=b">과목 B</a>
        <a href="board.php?board_type=c">과목 C</a>
      </div>
      <div class="header_row3">
        <?= "내가 좋아요한 게시글" ?>
      </div>
    </header>
    <main>
      [헤더 아래 페이지 내용]
      <?php
			// 1. Like 테이블에서 nickname가 'abc1234'인 행들의 post_id를 가져오는 쿼리
			$select_like_query = "SELECT post_id FROM `Like` WHERE user_id = '$user_id'";
			$result_like = mysqli_query($con, $select_like_query);

			if ($result_like) {
				// 가져온 post_id들을 저장할 배열 초기화
				$post_ids = array();

				// 결과에서 각 행의 post_id를 배열에 추가
				while ($row_like = mysqli_fetch_assoc($result_like))
					$post_ids[] = $row_like['post_id'];

				// 결과 해제
				mysqli_free_result($result_like);

				// 2. Post 테이블에서 위에서 가져온 post_id에 해당하는 행들을 가져오는 쿼리
				if (!empty($post_ids)) {
					$post_ids_string = implode(',', $post_ids); // post_id 배열을 쉼표로 구분된 문자열로 변환
			
					$select_post_query = "SELECT * FROM Post WHERE post_id IN ($post_ids_string)";
					$result_post = mysqli_query($con, $select_post_query);

					if ($result_post) {
						// Post 테이블에서 가져온 결과 사용
						while ($row_post = mysqli_fetch_assoc($result_post)) {
							print_r($row_post);
							print "<br>";
						}
						// 결과 해제
						mysqli_free_result($result_post);
					} else
						echo "Post 테이블에서 쿼리 실행 실패: " . mysqli_error($con);
				} else
					echo "Like 테이블에서 해당하는 nickname의 행이 없습니다.";
			} else
				echo "Like 테이블에서 쿼리 실행 실패: " . mysqli_error($con);
			?>
    </main>
  </body>

</html>
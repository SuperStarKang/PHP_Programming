<?php
	include("./config.php");
	include("./functions_by_limeojin.php");
	include("./session.php");

	$nickname = get_auth_session()['nickname'];

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
        <?= "내가 쓴 게시글" ?>
      </div>
    </header>
    <main>
      <?php	
		$query = "SELECT board_type, title, nickname, `time`, `like`, comment, hit FROM Post where nickname='$nickname'";

		$result = mysqli_query($con, $query) or die(mysqli_error($con));

		while ($comment = mysqli_fetch_assoc($result))
		{
			print_r($comment);
			print "<br>";
		}
	?>
    </main>
  </body>

</html>
<?php
include("./config.php");
include("./functions_by_limeojin.php");
include("./session.php");
include("./subject.php");

// 이 자리에서 추가 파일 include, 쿼리스트링 추출 등을 실행해주세요..


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
      </div>
      <div class="header_row2">
        <?php

        $board_types = array_keys($board_type_to_subject);
        foreach ($board_types as $board_type) {
          $subject = $board_type_to_subject[$board_type];
          echo "<a href='board.php?board_type=$board_type'>$subject</a>";
        }

        // 필요하면 넣고, 아님 말고
        // $board_type = (int) $_GET['board_type'];
        
        ?>
      </div>
      <div class="header_row3">
        <!-- 여기를 좀 채워주세요 -->
      </div>
    </header>
    <main>
      <!-- 페이지 내용 영역입니다.. -->
    </main>
  </body>

</html>
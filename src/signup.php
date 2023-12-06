<?php
include("./config.php");
include("./functions_by_limeojin.php");
include("./session.php");
include("./subject.php");

if (is_logged_in()) {
  alert_and_navigate("이미 로그인되어 있습니다. 사이트 메인 페이지로 돌아갑니다.", "./home.php");
}

?>

<html>

  <head>
    <style>
    <?php include("./global.css") ?><?php include("./header.css") ?>main {
      flex: 1;

      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    main form {
      display: flex;
      gap: 20px;
    }

    .form_inputs {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .form_input_item {}
    </style>
    <title>[여기다가 제목을 넣으세요]</title>
  </head>

  <body>
    <header>
      <div class="header_row1">
        <div>
          <a href="home.php" class="header_logo">세컴과</a>
          <span class="subtitle">세상의 모든 컴퓨터 과목</span>
        </div>
        <div class="header_top_right">
          <?= is_logged_in()
            ? '<a href="./logout_db.php">로그아웃</a><a href="./my-profile.php">내프로필</a>'
            : '<a href="./login.php">로그인</a><a href="./signup.php" style="font-weight:600">회원가입</a>';
          ?>
        </div>
      </div>
      <div class="header_row2">
        <?php

        $board_types = array_keys($board_type_to_subject);
        foreach ($board_types as $board_type) {
          $subject = $board_type_to_subject[$board_type];
          echo "<a href='board.php?board_type=$board_type'>$subject</a>";
        }

        ?>
      </div>
      <div class="header_row3">
        회원가입
      </div>
    </header>
    <main>
      <form action="signup_db.php" method="POST">
        <div class="form_inputs">
          <label for="id">아이디</label>
          <input name="id" id="id">
          <label for="password">비밀번호</label>
          <input name="password" id="password">
          <label for="password_confirm">비밀번호 확인</label>
          <input name="password_confirm" id="password_confirm">
          <label for="nickname">닉네임</label>
          <input name="nickname" id="nickname">
        </div>
        <button>회원가입</button>
      </form>
    </main>
  </body>

</html>
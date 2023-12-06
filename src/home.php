<?php
include("./config.php");
include("./functions_by_limeojin.php");
include("./session.php");
include("./subject.php");

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
    <?php include("./global.css");
    include("./header.css");
    include("./home.css") ?>
    /* [스타일 코드를 적으세요] */
    </style>
    <title>반갑습니다</title>
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
            : '<a href="./login.php">로그인</a><a href="./signup.php">회원가입</a>';
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
        HOT 게시판
      </div>
    </header>
    <main>
      <p class="table_title">🔥 가장 인기있는 게시글 TOP 5 🔥</p>

      <table>
        <tr>
          <th class="table_header_title">제목</th>
          <th class="table_header_board_type">과목명</th>
          <th class="table_header_writer">글쓴이</th>
          <th class="table_header_date">작성일</th>
          <th class="table_header_like">추천</th>
          <th class="table_header_comment">댓글</th>
          <th class="table_header_hit">조회</th>
        </tr>

        <?php
        $query = "SELECT * FROM Post ORDER BY `like` DESC LIMIT 5";

        $result = mysqli_query($con, $query) or die(mysqli_error($con));

        while ($row = $result->fetch_assoc()) {
          $board_type = $row['board_type'];
          $subject = $board_type_to_subject[intval($board_type)];

          $post_url = "view_post.php?post_id={$row['post_id']}";
          $title = $row['title'];
          $nickname = $row['nickname'];
          $time = date('m/d', strtotime($row['time']));
          $comment = $row['comment'];
          $like = $row['like'];
          $hit = $row['hit'];

          echo "<tr>";
          echo "<td class='table_cell_title'><a href=$post_url>{$title}</a></td>";
          echo "<td class='table_cell_board_type'>{$subject}</td>";
          echo "<td class='table_cell_writer'>{$nickname}</td>";
          echo "<td class='table_cell_date'>{$time}</td>";
          echo "<td class='table_cell_like'>{$like}</td>";
          echo "<td class='table_cell_comment'>{$comment}</td>";
          echo "<td class='table_cell_hit'>{$hit}</td>";
          echo "</tr>";
        }

        ?>

      </table>
    </main>
  </body>

</html>
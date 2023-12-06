<?php
include("./config.php");
include("./functions_by_limeojin.php");
include("./session.php");
include("./subject.php");

$nickname = get_auth_session()['nickname'];
$user_id = get_auth_session()['id'];

if (!is_logged_in()) {
  alert_and_navigate("로그인되어 있지 않습니다. 로그인 페이지로 이동합니다.", "./login.php");
}

?>

<html>

  <head>
    <style>
    <?php include("./global.css") ?><?php include("./header.css") ?><?php include("./my-profile.css") ?>
    </style>
    <title>내 프로필</title>
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
            ? '<a href="./logout_db.php">로그아웃</a><a href="./my-profile.php" style="font-weight:600">내프로필</a>'
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

        ?>
      </div>
      <div class="header_row3">
        내 프로필
      </div>
    </header>
    <main>
      <span class="profile-myinfo">
        <div>
          내 ID: <?= $user_id ?>
        </div>
        <div>
          내 닉네임: <?= $nickname ?>
        </div>
      </span>

      <div class="profile-articles">
        <div class="written_by_me">
          <div class="table_title">내가 작성한 글</div>
          <table>
            <tr>
              <th class="table_header_title">제목</th>
              <th class="table_header_board_type">과목명</th>
              <th class="table_header_date">작성일</th>
              <th class="table_header_like">추천</th>
              <th class="table_header_comment">댓글</th>
              <th class="table_header_hit">조회</th>
            </tr>

            <?php
            $query = "SELECT board_type, title, nickname, `time`, `like`, comment, hit, post_id FROM Post where nickname='$nickname'";

            $result = mysqli_query($con, $query) or die(mysqli_error($con));

            while ($row = mysqli_fetch_assoc($result)) {
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
              echo "<td class='table_cell_date'>{$time}</td>";
              echo "<td class='table_cell_like'>{$like}</td>";
              echo "<td class='table_cell_comment'>{$comment}</td>";
              echo "<td class='table_cell_hit'>{$hit}</td>";
              echo "</tr>";
            }
            ?>
          </table>
        </div>
        <div class="liked_by_me">
          <div class="table_title">내가 좋아요한 글</div>
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
                  while ($row = mysqli_fetch_assoc($result_post)) {
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
                  // 결과 해제
                  mysqli_free_result($result_post);
                } else
                  echo "Post 테이블에서 쿼리 실행 실패: " . mysqli_error($con);
              } else
                echo "Like 테이블에서 해당하는 nickname의 행이 없습니다.";
            } else
              echo "Like 테이블에서 쿼리 실행 실패: " . mysqli_error($con);
            ?>

          </table>
        </div>
      </div>
    </main>
  </body>

</html>
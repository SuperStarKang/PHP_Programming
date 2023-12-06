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
    <?php include("./global.css") ?><?php include("./header.css") ?>?>.ques_head {
      background-color: #f9e79d;
      text-align: center;
    }

    .input_td {
      background-color: #FEFCE2;
    }
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
        글 수정 페이지입니다.
      </div>
    </header>
    <main>
      <center>
        <table>
          <?PHP

          // ----------- 해당 글의 정보를 추출 ----------------
          $post_id = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;
          $query = "SELECT title, content, board_type FROM Post WHERE post_id=$post_id";

          $result = $con->query($query) or die("error occured!");

          list($title, $content, $board_type) = mysqli_fetch_array($result);
          ?>

          <form name="edit_form" method="post" action="./edit_post_db.php">
            <input type="hidden" name="post_id" value=<?= $post_id ?>>
            <input type="hidden" name="board_type" value=<?= $board_type ?>>
            <tr>
              <td class="ques_head">제목</td>
              <td class="input_td">
                <input type="text" name="title" value="<?= $title ?>" size="50" maxsize="255">
              </td>
            </tr>
            <tr>
              <td class="ques_head">내용</td>
              <td class="input_td">
                <textarea name="content" cols="60" rows="20"><?= $content ?></textarea>
              </td>
            </tr>
        </table>

        <table>
          <tr>
            <td align="center">
              <input type="submit" value="수정">
            </td>
            <td width="100" align="right">
              <?php
              function get_post_url($post_id)
              {
                return "view_post.php?post_id=" . $post_id;
              }
              ?>
              <a href="<?= get_post_url($post_id) ?>">취소</a>
            </td>
          </tr>
          </form>
        </table>
        <center>

    </main>
  </body>

</html>
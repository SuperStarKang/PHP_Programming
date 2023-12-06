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
if (!is_logged_in()) {
  alert_and_navigate("로그인되어 있지 않습니다. 로그인 페이지로 이동합니다.", "./login.php");
}
?>

<html>

  <head>
    <style>
    <?php include("./global.css") ?><?php include("./header.css") ?>
    /* [스타일 코드를 적으세요] */
    </style>
    <title>글쓰기</title>
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
        글쓰기 페이지입니다.
      </div>
    </header>



    <main>
      <?php
      $board_type = isset($_GET['board_type']) ? $_GET['board_type'] : '';
      ?>
      <html>

        <head>
          <link rel="stylesheet" type="text/css" href="./common_style.css">
          <style>
          .ques_head {
            background-color: #F9E79D;
            text-align: center;
          }

          .input_td {
            background-color: #FEFCE2;
          }
          </style>
          <title>게시판</title>
        </head>

        <body>
          <tr>
            <td width="100">&nbsp;</td>
            <td width="100" align="right">
            </td>
          </tr>
          <form name="write_form" method="post" action="write_db.php?board_type=<?= htmlspecialchars($board_type) ?>">
            <center>
              <table>
                <tr>
                  <td class="ques_head">제목</td>
                  <td class="input_td">
                    <input type="text" name="title" size="50" maxsize="255">
                  </td>
                </tr>
                <tr>
                  <td class="ques_head">내용</td>
                  <td class="input_td">
                    <textarea name="content" cols="60" rows="20"></textarea>
                  </td>
                </tr>
              </table>
              <table>
                <tr>
                  <td width="100">&nbsp;</td>
                  <td align="center"><input type="submit" value="입력"></td>
                  <td width="100" align="center">
                    <?php
                    function get_post_url($board_type)
                    {
                      return "board.php?board_type=" . $board_type;
                    }
                    ?>
                    <a href="<?= get_post_url($board_type) ?>">목록</a>
                  </td>
                </tr>
              </table>
            </center>
          </form>
        </body>

      </html>





    </main>
  </body>

</html>
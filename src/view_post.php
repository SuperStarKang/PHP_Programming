<?php
include("./config.php");
include("./functions_by_limeojin.php");
include("./session.php");
include("./subject.php");

// 이 자리에서 추가 파일 include, 쿼리스트링 추출 등을 실행해주세요..

// 이전 코드에서 사용된 부분
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$post_id = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;

// 데이터베이스에서 글의 정보 검색
$query = "SELECT user_id, board_type, nickname, title, content, `time`, hit, comment FROM Post WHERE post_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// 가져온 데이터 사용하기
$user_id = $row['user_id'];
$board_type = $row['board_type'];
$nickname = $row['nickname'];
$title = htmlspecialchars($row['title']);
$content = $tag_enable ? $row['content'] : htmlspecialchars($row['content']);
$hit = $row['hit'];
$comment = $row['comment'];
$time = $row['time'];

// 조회 수 증가
$query_update_hit = "UPDATE Post SET hit = hit + 1 WHERE post_id = ?";
$stmt_update_hit = $con->prepare($query_update_hit);
$stmt_update_hit->bind_param("i", $post_id);
$stmt_update_hit->execute();

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
    <?php include("./global.css") ?><?php include("./header.css") ?><?php include("./view_post.css") ?>
    </style>
    <title><?= $title ?></title>
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
        foreach ($board_types as $board_type_iterator) {
          $subject = $board_type_to_subject[$board_type_iterator];
          $link_class = $board_type_iterator == $board_type ? "focused" : 'not-focused';

          echo "<a class='$link_class' href='board.php?board_type=$board_type_iterator'>$subject</a>";
        }
        ?>
      </div>
      <div class="header_row3">
        글 읽기 페이지입니다.
      </div>
    </header>

    <main>

      <div class="post-header">
        <div class="post-header_row1">
          <?= $title ?>
        </div>
        <div class="post-header_row2">
          <div class="post-header_writer">
            글쓴이: <?= $nickname ?>
          </div>
          <div class="post-header_hit">
            조회수: <?= $hit ?>
          </div>
        </div>
      </div>
      <div class="post-content">
        <?= nl2br($content) ?>
      </div>
      <div class="post-like">
        <?php
        if (is_logged_in()) {
          ?>
        <form action="like_db.php" method="POST">
          <button class="post-like_button" type="submit" name="like_button">좋아요</button>
          <input type="hidden" name="post_id" value="<?= "$post_id" ?>">
        </form>

        <?php
        } else {
          ?>
        <div>좋아요(로그인하시면 좋아요 버튼이 생겨요): </div>
        <?php
        }
        ?>

        <?php
        // $con은 데이터베이스 연결 객체로 가정
        $query = "SELECT `like` FROM Post WHERE post_id = '$post_id'";
        $result = mysqli_query($con, $query);
        $likeCount = mysqli_fetch_assoc($result)['like'];
        ?>
        <div><?= $likeCount ?></div>
      </div>

      <?php
      function get_post_url($board_type)
      {
        return "board.php?board_type=" . $board_type;
      }
      ?>

      <div class="below-content">
        <button onclick="location.href='<?= get_post_url($board_type) ?>'">목록으로</button>

        <div class="post-show-if-written-by-me">
          <?php
          if (is_logged_in() && !strcmp($user_id, get_auth_session()['id'])) {
            ?>
          <form action="edit_post.php" method="GET">
            <input type="hidden" name="post_id" value="<?= $post_id ?>">
            <button type="submit">수정</button>
          </form>
          <form action="delete.php" method="GET">
            <input type="hidden" name="post_id" value="<?= $post_id ?>">
            <button type="submit">삭제</button>
          </form>
          <?php
          }
          ?>

        </div>
      </div>



      <div class="comments">
        <?php
        if (is_logged_in()) {
          ?>
        <form class="comment_form" method="post" action="add_comment.php">
          <textarea name="comment_content" class="comment_input" value="<?= "$comment_content" ?>"
            placeholder="댓글을 입력하세요"></textarea>
          <input type="submit" value="댓글 작성">
          <input type="hidden" name="post_id" value="<?= $post_id ?>">
        </form>
        <?php
        } else {
          ?>
        <div>로그인하시면 댓글을 등록할 수 있습니다.</div>

        <?php
        } ?>

        <?php
        # 해당 글의 댓글 가져옴
        $query = "SELECT * from Comment where post_id = '$post_id'";
        $result = mysqli_query($con, $query) or die(mysqli_error($con));

        while ($comment = mysqli_fetch_array($result)) {
          $comment_id = $comment['comment_id'];
          $comment_nickname = $comment['nickname'];
          $comment_content = $comment['content'];
          ?>
        <div class="comment">
          <div class="comment-header">
            <div> 댓글 쓴 사람: <?= $comment_nickname ?> </div><?php
                 if (is_logged_in()) {
                   if (!strcmp($comment_nickname, get_auth_session()['nickname'])) {
                     ?>
            <form action="delete_comment.php" method="GET" style="display:inline;">
              <input type="hidden" name="comment_id" value="<?= $comment_id ?>">
              <button type="submit">삭제</button>
            </form>
            <?php
                   }
                 }
                 ?>
          </div>
          <div class="comment-content"><?= $comment_content ?></div>

        </div>
        <?php
        }
        ?>

      </div>


    </main>
  </body>

</html>
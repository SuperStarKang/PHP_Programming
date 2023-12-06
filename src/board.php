<?php
include("./config.php");
include("./functions_by_limeojin.php");
include("./session.php");
include("./subject.php");

$board_type = (int) $_GET['board_type'];

?>

<html>

  <head>
    <style>
    <?php include("./global.css");
    ?><?php include("./header.css");
    ?><?php include("./board.css");
    ?>
    </style>
    <title><?= $board_type_to_subject[$board_type] ?> 게시판입니다.</title>
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
          $link_class = $board_type == $_GET['board_type'] ? "focused" : 'not-focused';

          echo "<a class='$link_class' href='board.php?board_type=$board_type'>$subject</a>";
        }

        $board_type = (int) $_GET['board_type'];

        ?>
      </div>
      <div class="header_row3">
        <div class="header_board_title">
          <?= $board_type_to_subject[$board_type] ?> 게시판입니다.
        </div>
        <form class="header_search" method="GET" action=<?= "board.php" ?>>
          <select name="search_condition" class="header_search_options">
            <option value="title">제목</option>
            <option value="user">글쓴이</option>
            <option value="content">내용</option>
          </select>
          <input type="text" name="search_query" placeholder="검색어 입력" class="header_search_text_input">
          <input type="hidden" name="board_type" value=<?= $board_type ?>>
          <button>검색</button>
        </form>

      </div>
    </header>
    <main>
      <?php
      $search_condition = isset($_GET['search_condition']) ? $_GET['search_condition'] : '';
      $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

      $posts_per_page = $rows_page;
      $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

      $start_index = ($current_page - 1) * $posts_per_page;
      $query = "SELECT * FROM Post WHERE board_type = $board_type ";

      if (!empty($search_condition) && !empty($search_query)) {
        $search_query = $con->real_escape_string($search_query);
        switch ($search_condition) {
          case 'title':
            $query .= " AND title LIKE '%$search_query%'";
            break;
          case 'user':
            $query .= " AND nickname LIKE '%$search_query%'";
            break;
          case 'content':
            $query .= " AND content LIKE '%$search_query%'";
            break;
        }
      }
      $query .= "ORDER BY post_id DESC";

      $result = $con->query($query);
      $total_posts = $result->num_rows; // 변경된 부분
      
      $total_pages = ceil($total_posts / $posts_per_page);
      $query .= " LIMIT $start_index, $posts_per_page";

      $result = $con->query($query);
      ?>

      <div class="글쓰기wrapper">
        <a href=<?= "./write.php?board_type=$board_type" ?>>글쓰기</a>
      </div>


      <table>

        <tr>
          <th class="table_header_title">제목</th>
          <th class="table_header_writer">글쓴이</th>
          <th class="table_header_date">작성일</th>
          <th class="table_header_like">추천</th>
          <th class="table_header_comment">댓글</th>
          <th class="table_header_hit">조회</th>
        </tr>

        <?php
        $remaining = 10;

        while ($row = $result->fetch_assoc()) {
          $post_url = "view_post.php?post_id={$row['post_id']}";
          $title = $row['title'];
          $nickname = $row['nickname'];
          $time = date('m/d', strtotime($row['time']));
          $comment = $row['comment'];
          $like = $row['like'];
          $hit = $row['hit'];

          echo "<tr>";
          echo "<td class='table_cell_title'><a href=$post_url>{$title}</a></td>";
          echo "<td class='table_cell_writer'>{$nickname}</td>";
          echo "<td class='table_cell_date'>{$time}</td>";
          echo "<td class='table_cell_like'>{$like}</td>";
          echo "<td class='table_cell_comment'>{$comment}</td>";
          echo "<td class='table_cell_hit'>{$hit}</td>";
          echo "</tr>";

          $remaining--;
        }

        while ($remaining--)
          echo "<tr><td class='table_cell_title'>----</td><td class='table_cell_writer'>--</td><td class='table_cell_date'>--</td><td class='table_cell_like'>--</td><td class='table_cell_comment'>---</td><td class='table_cell_hit'>---</td></tr>";
        ?>
      </table>

      <div class="links">
        <div class="pagination">
          <?php
          $total_pages = ceil($total_posts / $posts_per_page);


          if ($current_page > 1) {
            echo "<a href='board.php?board_type=$board_type&page=" . ($current_page - 1) . "&search_condition=$search_condition&search_query=$search_query'>이전 </a>";
          }

          for ($i = max(1, $current_page - 1); $i <= min($total_pages, $current_page + 1); $i++) {
            $link_class = $i ==$current_page ? "focused" : 'not-focused';

            echo "<a class='$link_class' href='board.php?board_type=$board_type&page=$i&search_condition=$search_condition&search_query=$search_query'> $i </a>";

          }
          if ($current_page < $total_pages) {
            echo "<a href='board.php?board_type=$board_type&page=" . ($current_page + 1) . "&search_condition=$search_condition&search_query=$search_query'> 다음</a>";
          }


          ?>
        </div>

      </div>

    </main>
  </body>

</html>
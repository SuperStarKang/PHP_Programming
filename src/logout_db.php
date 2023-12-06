<?php

include("./config.php");
include("./functions_by_limeojin.php");
include("./session.php");

set_auth_session(false, null, null);
alert_and_navigate("로그아웃 되었습니다.", "./home.php");

?>
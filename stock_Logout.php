<?php
session_start();
session_unset();
session_destroy();
echo "log out success！Leading to homepage！<br/>";
echo '<meta http-equiv=REFRESH CONTENT=1;url=stock_login.php>';
exit;
?>
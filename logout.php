<?php
session_start();
session_unset();
session_destroy();
unset($_COOKIE['cookie']);
setcookie('cookie', null, -1);
Header('Location: login.php');
?>

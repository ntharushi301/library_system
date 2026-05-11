<?php
session_start();
session_destroy();
header("Location: /library_system/auth/login.php");
exit();
?>

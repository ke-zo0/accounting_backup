<?php
session_start();
session_destroy();
header("Location: /finals_integrated/Login/login.php");
exit();
?> 
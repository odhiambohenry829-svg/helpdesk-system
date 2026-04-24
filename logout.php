<?php
session_start();
session_destroy();   // This logs the user out

header("Location: login.php");
exit();
?>
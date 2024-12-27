<?php
session_start();
session_destroy();
header("location: /WebDev/Online-Voting-System/index.php");
?>
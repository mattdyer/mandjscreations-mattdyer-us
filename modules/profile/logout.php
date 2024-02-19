<?php
	include("/var/www/html/modules/AppInit.php");
	unset($_SESSION['User']);
	header('Location: /modules/profile/login.php');
?>
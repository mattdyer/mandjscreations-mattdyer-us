<?php
	include("/var/www/html/modules/AppInit.php");
	unset($_SESSION['User']);
	header('Location: /modules/admin/login.php');
?>
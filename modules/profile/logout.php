<?php
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	unset($_SESSION['User']);
	header('Location: /modules/profile/login.php');
?>
<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	unset($_SESSION['User']);
	header('Location: /modules/profile/login.php');
?>
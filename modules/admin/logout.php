<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	unset($_SESSION['User']);
	header('Location: /modules/admin/login.php');
?>
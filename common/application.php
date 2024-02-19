<?php 
	define('SiteRoot','/var/www/html');
	/*include("/var/www/html/common/classlibrary.php");*/
	function LoadClass($ClassPath){
		require_once($ClassPath . '.php');
		$ClassName = basename($ClassPath);
		return new $ClassName;
	}
	
	$site = LoadClass(SiteRoot . '/modules/classes/Site');
	$site->load(1);
?>
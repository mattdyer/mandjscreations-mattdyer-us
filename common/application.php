<?php 
	define('SiteRoot','/home/matt/websites/mandjscreations.com');
	/*include("/home/matt/websites/mandjscreations.com/common/classlibrary.php");*/
	function LoadClass($ClassPath){
		require_once($ClassPath . '.php');
		$ClassName = basename($ClassPath);
		return new $ClassName;
	}
	
	$site = LoadClass(SiteRoot . '/modules/classes/Site');
	$site->load(1);
?>
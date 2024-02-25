<?php 
	define('SiteRoot', _SERVER['DOCUMENT_ROOT']);
	
	function LoadClass($ClassPath){
		require_once($ClassPath . '.php');
		$ClassName = basename($ClassPath);
		return new $ClassName;
	}
	
	$site = LoadClass(SiteRoot . '/modules/classes/Site');
	$site->load(1);
?>
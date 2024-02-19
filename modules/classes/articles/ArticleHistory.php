<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class ArticleHistory extends Record{
		function __construct(){
			record::__construct('ArticleHistory','mandjscreations','mandjsdb', 'root', 'example');
		}
	}
?>
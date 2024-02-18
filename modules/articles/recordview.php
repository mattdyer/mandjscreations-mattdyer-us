<?php
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	try{
		$ArticleID = $_GET['ArticleID'];
		$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
		$article->load($ArticleID);
	}
	catch(Exception $e){
		exit;
	}
	$article->set('Views',$article->get('Views') + 1);
	$article->set('Content',addslashes($article->get('Content')));
	$article->save();
?>
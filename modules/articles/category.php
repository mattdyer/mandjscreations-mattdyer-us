<?php
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	$CategoryID = $_GET['CategoryID'];
	$category = LoadClass(SiteRoot . '/modules/classes/articles/Category');
	try{
		$category->load($CategoryID);
	}
	catch(Exception $e){
		header('Location: /modules/articles/index.php');
	}
	//$category->load($CategoryID);
	
	if (!array_key_exists('Redirected', $_GET)){
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $category->MakeLink());
	}
	
	$Articles = $category->GetArticles();
	
	ob_start();
		echo '<div class="bodyContentSection">';
		echo '<h2>' . $category->get('Name') . '</h2>';
		echo '<div class="BreadCrumb">&gt; <a href="/">Home</a> &gt; <a href="/modules/articles/index.php">Articles</a> &gt; ' . $category->get('Name') . '</div>';
		echo '<div class="MainContent ArticleList">';
		foreach($Articles AS $key => $value){
			$ThisArticleLink = $value->MakeLink();
			$ThisArticleTitle = $value->get('Title');
			$ThisArticleContent = $value->get('Content');
			$ThisArticleContent = substr(strip_tags($ThisArticleContent),0,250);
			echo '<div><a href="' . $ThisArticleLink . '">' . $ThisArticleTitle . '</a> - <span class="articleDate">' . $value->FormatDate($value->get('DateEntered')) . '</span></div>'; 
			echo '<div>' . $ThisArticleContent . '...</div>';
		}
		echo '</div>';
		echo '</div>';
		$BodyContent = ob_get_contents();
	
	ob_end_clean();

	include(SiteRoot . "/common/template.php");
?>
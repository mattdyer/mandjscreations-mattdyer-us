<?php
	if(!isset($Template)){
		$Template = array();
	}
	if(!isset($Template['Title'])){
		$Template['Title'] = '';
	}
	if(!isset($Template['Metatags'])){
		$Template['Metatags'] = array();
	}
	if(!isset($Template['TopText'])){
		$Template['TopText'] = '';
	}
	
	$Page = LoadClass(SiteRoot . '/modules/classes/seo/Page');
	try{
		$Page->LoadByScriptName($_SERVER["SCRIPT_NAME"],$_SERVER['QUERY_STRING'],$site->get('SiteID'));
	}catch(Exception $e){
	}
	
	if(strlen($Page->get('Title')) > 0){
		$Template['Title'] = $Page->get('Title');
		$Template['Metatags']['description'] = $Page->get('Description');
		$Template['Metatags']['keywords'] =$Page->get('Keywords');
	}else{
		switch($_SERVER["SCRIPT_NAME"]){
			case '/modules/articles/category.php':
				$Template['Title'] = $category->get('Name') . ' - ' . $Articles[0]->get('Title');
				$Template['Metatags']['description'] = substr(strip_tags($Articles[0]->get('Content')),0,255);
				$Template['TopText'] = substr(strip_tags($Articles[0]->get('Content')),0,255);
				$Template['Metatags']['keywords'] = $Articles[0]->GenerateKeywords();
				/*$Page->set('PageType','Articles_Category');
				$Page->set('Name',$category->get('Name'));
				$Page->set('SEOURL',$category->MakeLink());
				$Page->set('ScriptName','Articles_Category');*/
				break;
			case '/modules/articles/article.php':
				$Template['Title'] = $category->get('Name') . ' - ' . $article->FormatDate($article->get('DateEntered')) . ' - ' . $article->get('Title');
				$Template['TopText'] = substr(strip_tags($article->get('Content')),0,255);
				$Template['Metatags']['description'] = substr(strip_tags($article->get('Content')),0,255);
				$Template['Metatags']['keywords'] = $article->GenerateKeywords();
				break;
			case '/modules/profile/index.php':
				$Template['Title'] = $ProfileUser->get('FirstName') . " " . $ProfileUser->get('LastName') . " MAD Computing User Profile";
				$Template['Metatags']['description'] = "The User Profile is a place for " . $ProfileUser->get('FirstName') . " " . $ProfileUser->get('LastName') . " to manage information stored by MAD Computing and see a history of site activity for this user account. For example article comment history and friend activity.";
				$Template['Metatags']['keywords'] = $ProfileUser->get('FirstName') . " " . $ProfileUser->get('LastName') . ", Profile, User Profile, MAD Computing Users, Directory, User List, Article Comments, Contact Information, Friends";
				break;
		}
	}
	
?>
<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Category extends Record{
		function __construct(){
			record::__construct('Categories','mandjscreations','localhost','root','//att1');
		}
		
		function GetCategories(){
			$Categories = $this->DoQuery("SELECT CategoryID FROM Categories WHERE ParentID = $this->get('CategoryID');");
			
			$CategoryArray = array();
			
			while($row = mysql_fetch_array($Categories)){
				$category = new Category();
				$category->load($row['CategoryID']);
				$CategoryArray[] = $category;
			}
			
			return $CategoryArray;
		}
		
		function GetArticles(){
			$CategoryID = $this->get('CategoryID');
			//$Articles = $this->DoQuery("SELECT ArticleID FROM Articles WHERE CategoryID = $CategoryID ORDER BY DateEntered DESC");
			$Articles = $this->DoQuery("SELECT ArticleID FROM Articles WHERE CategoryID = $CategoryID ORDER BY DateEntered DESC");
			
			$ArticleArray = array();
			
			while($row = mysql_fetch_array($Articles)){
				$HistoryFound = false;
				$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
				$article->load($row['ArticleID']);
				foreach($ArticleArray AS $key => $value){
					if($value->get('ArticleHistoryID') == $article->get('ArticleHistoryID')){
						//$ArticleArray[$key] = $article;
						$HistoryFound = true;
						break;
					}
				}
				if(!$HistoryFound){
					$ArticleArray[] = $article;
				}
			}
			
			return $ArticleArray;
		}
		function MakeLink(){
			$Link = "/category/" . $this->get('CategoryID') . "/" . preg_replace('/[^0-9a-zA-Z-]+/','-',$this->get('Name'));
			
			return $Link;
		}
	}
?>
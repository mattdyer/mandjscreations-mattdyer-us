<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Articles extends Record{
		function __construct(){
			record::__construct('Articles_Settings','mandjscreations','localhost','root','//att1');
		}
		
		function LoadBySiteID($SiteID){
			$Article_Settings = $this->DoQuery("SELECT SettingsID FROM Articles_Settings WHERE SiteID = " . $SiteID);
			
			while($row = mysql_fetch_array($Article_Settings)){
				$this->load($row['SettingsID']);
			}
		}
		
		function GetCategories(){
			$Categories = $this->DoQuery("SELECT CategoryID FROM Categories WHERE ParentID IS NULL AND SiteID = " . $this->get('SiteID'));
			
			$CategoryArray = array();
			
			while($row = mysql_fetch_array($Categories)){
				$category = LoadClass(SiteRoot . '/modules/classes/articles/Category');
				$category->load($row['CategoryID']);
				$CategoryArray[] = $category;
			}
			
			return $CategoryArray;
		}
		
		function GetArticles(){
			$Articles = $this->DoQuery("SELECT ArticleID 
									   FROM Articles A 
									   LEFT OUTER JOIN Categories C 
									   ON A.CategoryID = C.CategoryID 
									   WHERE C.SiteID = " . $this->get('SiteID'));
			
			$ArticleArray = array();
			
			while($row = mysql_fetch_array($Articles)){
				$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
				$article->load($row['ArticleID']);
				$ArticleArray[] = $article;
			}
			
			return $ArticleArray;
		}
		
		function GetPopularArticles(){
			$Articles = $this->DoQuery("SELECT ArticleID 
									   FROM Articles A 
									   LEFT OUTER JOIN Categories C 
									   ON A.CategoryID = C.CategoryID 
									   WHERE C.SiteID = " . $this->get('SiteID') . "
									   ORDER BY A.Views DESC, A.DateEntered DESC;");
			
			$ArticleArray = array();
			
			while($row = mysql_fetch_array($Articles)){
				$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
				$article->load($row['ArticleID']);
				$ArticleArray[] = $article;
			}
			
			return $ArticleArray;
		}
		
		function GetRecentArticles(){
			$Articles = $this->DoQuery("SELECT ArticleID 
									   FROM Articles A 
									   LEFT OUTER JOIN Categories C 
									   ON A.CategoryID = C.CategoryID 
									   WHERE C.SiteID = " . $this->get('SiteID') . "
									   ORDER BY A.DateEntered DESC
									   Limit 100;");
			
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
				if(count($ArticleArray) >= 10){
					break;
				}
			}
			
			return $ArticleArray;
		}
	}
?>
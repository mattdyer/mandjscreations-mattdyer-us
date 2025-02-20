<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Article extends Record{
		function __construct(){
			record::__construct('Articles','mandjscreations','mandjsdb','root','example');
		}
		
		function beforeSave(){
			if ($this->fields['Views'] == '' or strlen($this->fields[$this->IDColumn]) == 0){
				$this->set('Views',0);
			}
			if (strlen($this->fields[$this->IDColumn]) == 0){
				$this->set('DateEntered',date("Y-m-d H:i:s", time()) );
			}
		}
		
		function GetComments(){
			$Comments = $this->DoQuery("SELECT CommentID FROM Comments WHERE ArticleID = ? ORDER BY DateEntered", [$this->get('ArticleID')], 'i');
			
			$CommentArray = array();
			
			while($row = $Comments->fetch_array()){
				$comment = LoadClass(SiteRoot . '/modules/classes/articles/Comment');
				$comment->load($row['CommentID']);
				$CommentArray[] = $comment;
			}
			
			return $CommentArray;
		}
		
		function GetHistory(){
			$ArticleArray = array();
			
			if(strlen($this->get('ArticleHistoryID')) > 0){
				$Articles = $this->DoQuery("SELECT ArticleID FROM Articles WHERE ArticleHistoryID = ? AND ArticleID != ? ORDER BY DateEntered", [$this->get('ArticleHistoryID'), $this->get('ArticleID')], 'ii');
				
				while($row = $Articles->fetch_array()){
					$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
					$article->load($row['ArticleID']);
					$ArticleArray[] = $article;
				}
			}
			return $ArticleArray;
		}
		
		function GenerateKeywords(){
			$Content = $this->get('Content');
			$ContentArray = Explode(' ',preg_replace("/(?s)[^0-9a-zA-Z+]/"," ",strip_tags($Content)));
			$Keywords = '';
			for($i=0;$i < min(count($ContentArray),150);$i++){
				if(strlen($ContentArray[$i]) > 6){
					$Keywords .= $ContentArray[$i] . ",";
				}
			}
			
			return $Keywords;
		}
		function MakeLink(){
			$Link = "/article/" . $this->get('ArticleID') . "/" . preg_replace('/[^0-9a-zA-Z-]+/','-',$this->get('Title'));
			
			return $Link;
		}
	}
?>
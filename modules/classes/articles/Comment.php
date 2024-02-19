<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Comment extends Record{
		function __construct(){
			record::__construct('Comments','mandjscreations','mandjsdb', 'root', 'example');
		}
		
		function beforeSave(){
			if ($this->IsNewRecord()){
				$this->set('DateEntered',$this->SQLDate(time()));
				$this->set('Deleted',0);
			}
		}
		function afterSave(){
			
			ob_start();
		
			echo "ArticleID: " . $_GET['ArticleID'] . "\n\n";
			echo "Name: " . $_POST['Name'] . "\n\n";
			echo "Comment: " . $_POST['Content'];
			
			$Comment = ob_get_contents();
			ob_end_clean();
			
			mail('madmatt1220@gmail.com','Comment Added',$Comment,'From: matt@mandjscreations.com');
		}
		
		function getArticle(){
			$Articles = $this->DoQuery("SELECT ArticleID FROM Articles WHERE ArticleID = " . $this->get('ArticleID') . " ORDER BY DateEntered DESC");
			
			while($row = mysql_fetch_array($Articles)){
				$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
				$article->load($row['ArticleID']);
			}
			
			return $article;
		}
	}
?>
<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Page extends Record{
		function __construct(){
			record::__construct('SEO_Pages','mandjscreations','mandjsdb','root','example');
		}
		
		function LoadByScriptName($ScriptName, $QueryString, $SiteID){
			
			$LikeString = $ScriptName . "?" . $QueryString . "%";

			$FindPage = $this->DoQuery("SELECT PageID FROM SEO_Pages WHERE SiteID = ? AND ScriptName = ?", [$SiteID, $ScriptName], 'is');
			$FindPage2 = $this->DoQuery("SELECT PageID FROM SEO_Pages WHERE SiteID = ? AND ScriptName LIKE ?", [$SiteID, $LikeString], 'is');
			
			if($FindPage->num_rows == 0 && $FindPage2->num_rows == 0){
				throw new Exception('No record was found for script name ' . $ScriptName);
			}else{
				if($FindPage2->num_rows > 0){
					while($row = $FindPage2->fetch_array()){
						$this->load($row['PageID']);
					}
				}else{
					while($row = $FindPage->fetch_array()){
						$this->load($row['PageID']);
					}
				}
				
			}
		}
	}
?>
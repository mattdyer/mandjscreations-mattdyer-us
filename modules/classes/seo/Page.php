<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Page extends Record{
		function __construct(){
			record::__construct('SEO_Pages','mandjscreations','localhost','root','//att1');
		}
		
		function LoadByScriptName($ScriptName, $QueryString, $SiteID){
			
			$FindPage = $this->DoQuery("SELECT PageID FROM SEO_Pages WHERE SiteID = " . $SiteID . " AND ScriptName = '" . $ScriptName . "'");
			$FindPage2 = $this->DoQuery("SELECT PageID FROM SEO_Pages WHERE SiteID = " . $SiteID . " AND ScriptName LIKE '" . $ScriptName . '?' . $QueryString . "%'");
			
			if(mysql_num_rows($FindPage) == 0 && mysql_num_rows($FindPage2) == 0){
				throw new Exception('No record was found for script name ' . $ScriptName);
			}else{
				if(mysql_num_rows($FindPage2) > 0){
					while($row = mysql_fetch_array($FindPage2)){
						$this->load($row['PageID']);
					}
				}else{
					while($row = mysql_fetch_array($FindPage)){
						$this->load($row['PageID']);
					}
				}
				
			}
		}
	}
?>
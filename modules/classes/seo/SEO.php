<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class SEO extends Record{
		function __construct(){
			record::__construct('SEO_Settings','mandjscreations','localhost','root','//att1');
		}
		
		function LoadBySiteID($SiteID){
			$SEO_Settings = $this->DoQuery("SELECT SettingsID FROM SEO_Settings WHERE SiteID = " . $SiteID);
			
			while($row = mysql_fetch_array($SEO_Settings)){
				$this->load($row['SettingsID']);
			}
		}
		
		function GetPages(){
			$Pages = $this->DoQuery("SELECT PageID FROM SEO_Pages WHERE SiteID = " . $this->get('SiteID') . " ORDER BY PageType, Name");
			
			$PageArray = array();
			
			while($row = mysql_fetch_array($Pages)){
				$Page = LoadClass(SiteRoot . '/modules/classes/seo/Page');
				$Page->load($row['PageID']);
				$PageArray[] = $Page;
			}
			
			return $PageArray;
		}
		
	}
?>
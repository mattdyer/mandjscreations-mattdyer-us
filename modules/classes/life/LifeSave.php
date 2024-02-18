<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class LifeSave extends Record{
		function __construct(){
			record::__construct('LifeSaves','mandjscreations','localhost','root','//att1');
		}
		
		function beforeSave(){
			if ($this->IsNewRecord()){
				$this->set('DateEntered',$this->SQLDate(time()));
			}
		}
		
		function getAllSaves(){
			$SaveQuery = $this->DoQuery("SELECT LifeSaveID, Name FROM LifeSaves WHERE UserID = " . $this->get('UserID'));
			
			$LifeSaves = array();
			
			while($row = mysql_fetch_array($SaveQuery)){
				$LifeSave['LifeSaveID'] = $row['LifeSaveID'];
				$LifeSave['Name'] = htmlentities($row['Name']);
				
				$LifeSaves[] = $LifeSave;
			}
			
			return $LifeSaves;
		}
	}
?>
<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Player extends Record{
		function __construct(){
			record::__construct('JSRTS_Players','mandjscreations','mandjsdb', 'root', 'example');
		}
		
		function beforeSave(){
			if ($this->IsNewRecord()){
				$this->set('DateEntered',$this->SQLDate(time()));
			}
		}
		
		function getActions(){
			$Actions = $this->DoQuery("SELECT A.ActionID, A.Type, A.Data FROM JSRTS_Actions A LEFT OUTER JOIN JSRTS_PlayerActionComplete PAC ON A.ActionID = PAC.ActionID AND PAC.PlayerID = " . $this->get('PlayerID') . " WHERE A.GameID = " . $this->get('GameID') . " AND PAC.PlayerID IS NULL");
			
			
			
			$ActionArray = array();
			
			while($row = mysql_fetch_array($Actions)){
				$JSAction = array("ActionID" => $row['ActionID'],"Type" => $row['Type'],"Data" => $row['Data']);
				$ActionArray[] = $JSAction;
			}
			
			return $ActionArray;
		}
	}
?>
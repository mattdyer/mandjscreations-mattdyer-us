<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class PlayerActionComplete extends Record{
		function __construct(){
			record::__construct('JSRTS_PlayerActionComplete','mandjscreations','mandjsdb', 'root', 'example');
		}
		
		function beforeSave(){
			if ($this->IsNewRecord()){
				$this->set('DateEntered',$this->SQLDate(time()));
			}
		}
		
	}
?>
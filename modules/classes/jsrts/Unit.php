<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Unit extends Record{
		function __construct(){
			record::__construct('JSRTS_Units','mandjscreations','localhost','root','//att1');
		}
		
		function beforeSave(){
			if ($this->IsNewRecord()){
				$this->set('DateEntered',$this->SQLDate(time()));
			}
		}
		
	}
?>
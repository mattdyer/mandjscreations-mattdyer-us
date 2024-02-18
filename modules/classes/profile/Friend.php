<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Friend extends Record{
		function __construct(){
			record::__construct('Profile_Friends','mandjscreations','localhost','root','//att1');
		}
		
		function beforeSave(){
			if ($this->IsNewRecord()){
				$this->set('DateEntered',$this->SQLDate(time()));
			}
		}
	}
?>
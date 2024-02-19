<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Friend extends Record{
		function __construct(){
			record::__construct('Profile_Friends','mandjscreations','mandjsdb', 'root', 'example');
		}
		
		function beforeSave(){
			if ($this->IsNewRecord()){
				$this->set('DateEntered',$this->SQLDate(time()));
			}
		}
	}
?>
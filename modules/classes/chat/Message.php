<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Message extends Record{
		function __construct(){
			record::__construct('Chat_Messages','mandjscreations','mandjsdb', 'root', 'example');
		}
		
		function beforeSave(){
			if ($this->IsNewRecord()){
				$this->set('DateEntered',$this->SQLDate(time()));
			}
		}
	}
?>
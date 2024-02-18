<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class InvoiceItem extends Record{
		function __construct(){
			record::__construct('Business_InvoiceItems','mandjscreations','localhost','root','//att1');
		}
		
		function beforeSave(){
			if (strlen($this->fields[$this->IDColumn]) == 0){
				$this->set('DateEntered',date("Y-m-d H:i:s", time()) );
			}
		}
	}
?>
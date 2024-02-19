<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Customer extends Record{
		function __construct(){
			record::__construct('Business_Customers','mandjscreations','mandjsdb', 'root', 'example');
		}
		
		function beforeSave(){
			if (strlen($this->fields[$this->IDColumn]) == 0){
				$this->set('DateEntered',date("Y-m-d H:i:s", time()) );
			}
		}
		
		function GetInvoices(){
			$Invoices = $this->DoQuery("SELECT InvoiceID FROM Business_Invoices WHERE CustomerID = " . $this->get('CustomerID') . " ORDER BY DateEntered");
			
			$InvoiceArray = array();
			
			while($row = mysql_fetch_array($Invoices)){
				$invoice = LoadClass(SiteRoot . '/modules/classes/business/Invoice');
				$invoice->load($row['InvoiceID']);
				$InvoiceArray[] = $invoice;
			}
			
			return $InvoiceArray;
		}
		
	}
?>
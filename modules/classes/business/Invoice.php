<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Invoice extends Record{
		function __construct(){
			record::__construct('Business_Invoices','mandjscreations','mandjsdb', 'root', 'example');
		}
		
		function beforeSave(){
			if (strlen($this->fields[$this->IDColumn]) == 0){
				$this->set('DateEntered',date("Y-m-d H:i:s", time()) );
			}
		}
		
		function GetInvoiceItems(){
			$InvoiceItems = $this->DoQuery("SELECT InvoiceItemID FROM Business_InvoiceItems WHERE InvoiceID = " . $this->get('InvoiceID') . " ORDER BY DateEntered");
			
			$InvoiceItemArray = array();
			
			while($row = mysql_fetch_array($InvoiceItems)){
				$invoiceitem = LoadClass(SiteRoot . '/modules/classes/business/InvoiceItem');
				$invoiceitem->load($row['InvoiceItemID']);
				$InvoiceItemArray[] = $invoiceitem;
			}
			
			return $InvoiceItemArray;
		}
		
		function getTotal(){
			$Total = $this->DoQuery("SELECT SUM(Total) AS InvoiceTotal FROM Business_InvoiceItems WHERE InvoiceID = " . $this->get('InvoiceID'));
			
			while($row = mysql_fetch_array($Total)){
				$InvoiceTotal = $row['InvoiceTotal'];
			}
			
			return $InvoiceTotal;
		}
		
		function formatDate($ColumnName){
			$Date = $this->get($ColumnName);
			$DatePart = explode(' ',$Date);
			//$TimePart = explode(' ',$Date)[1];
			$DateParts = explode('-',$DatePart[0]);
			$TimeParts = explode(':',$DatePart[1]);
			
			return $DateParts[1] . '/' . $DateParts[2] . '/' . $DateParts[0];
		}
	}
?>
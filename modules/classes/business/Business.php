<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Business extends Record{
		function __construct(){
			record::__construct('Business_Settings','mandjscreations','mandjsdb','root','example');
		}
		
		function LoadBySiteID($SiteID){
			$Business_Settings = $this->DoQuery("SELECT SettingsID FROM Business_Settings WHERE SiteID = ?", [$SiteID], 'i');
			
			while($row = $Business_Settings->fetch_array()){
				$this->load($row['SettingsID']);
			}
		}
		
		function GetCustomers(){
			$Customers = $this->DoQuery("SELECT CustomerID FROM Business_Customers ORDER BY FirstName");
			
			$CustomerArray = array();
			
			while($row = mysql_fetch_array($Customers)){
				$customer = LoadClass(SiteRoot . '/modules/classes/business/Customer');
				$customer->load($row['CustomerID']);
				$CustomerArray[] = $customer;
			}
			
			return $CustomerArray;
		}
		
	}
?>
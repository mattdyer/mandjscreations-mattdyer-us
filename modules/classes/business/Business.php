<?php
	require_once(SiteRoot . '/modules/classes/Record.php');
	class Business extends Record{
		function __construct(){
			record::__construct('Business_Settings','mandjscreations','localhost','root','//att1');
		}
		
		function LoadBySiteID($SiteID){
			$Business_Settings = $this->DoQuery("SELECT SettingsID FROM Business_Settings WHERE SiteID = " . $SiteID);
			
			while($row = mysql_fetch_array($Business_Settings)){
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
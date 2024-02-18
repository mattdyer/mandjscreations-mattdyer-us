<?php
	$RequireLogin = true;
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	
	$Customers = $site->Modules['Business']->GetCustomers();
	
	ob_start();
		echo '<a href="/modules/business/admin/customers/manage.php">Add New Customer</a><br />';
		echo '<a href="/modules/business/admin/invoices/manage.php">Add New Invoice</a><br />';
		
		echo '<h3>Customers</h3>';
		
		foreach($Customers AS $key => $value){
			$ThisCustomerID = $value->get('CustomerID');
			$ThisCustomerFirstName = $value->get('FirstName');
			
			echo '<a href="/modules/business/admin/customers/manage.php?CustomerID=' . $ThisCustomerID . '">' . $ThisCustomerFirstName . '</a><br />';
		}
		
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>
 
<?php
	include("/home/matt/websites/mandjscreations.com/common/admintemplate.php");
?>
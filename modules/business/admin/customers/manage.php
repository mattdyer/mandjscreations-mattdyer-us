<?php
	$RequireLogin = true;
	include("/var/www/html/modules/AppInit.php");
	
	$customer = LoadClass(SiteRoot . '/modules/classes/business/Customer');
	
	if (array_key_exists('CustomerID', $_GET)) {
		$CustomerID = $_GET['CustomerID'];
		$customer->load($CustomerID);
	}
	
	if (array_key_exists('SaveCustomer', $_POST)) {
		$customer->updateAttributes($_POST);
		$customer->save();
		header('Location: ' . $_SERVER['PHP_SELF'] . '?CustomerID=' . $customer->get('CustomerID'));
	}
	
	ob_start();
?>
	<div><a href="/modules/business/admin/index.php">Back</a></div>
	<form method="post" enctype="multipart/form-data" name="" action="<?php echo $_SERVER['PHP_SELF']; echo '?'; echo $_SERVER['QUERY_STRING']; ?>">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>First Name:<br /><input type="text" name="FirstName" value="<?php echo $customer->get('FirstName'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right"><input type="submit" name="SaveCustomer" value="Save" /></td>
			</tr>
		</table>
	</form>
<?php
	if(!$customer->IsNewRecord()){
		$Invoices = $customer->GetInvoices();
		
		echo '<h3>Invoices</h3>';
		
		foreach($Invoices AS $key => $value){
			$ThisInvoiceID = $value->get('InvoiceID');
			$ThisDate = $value->formatDate('DateEntered');
			
			echo '<a href="/modules/business/admin/invoices/manage.php?InvoiceID=' . $ThisInvoiceID . '">' . $ThisInvoiceID . '. ' . $ThisDate . '</a><br />';
		}
		
	}
?>
<?php
		$BodyContent = ob_get_contents();
	ob_end_clean();
?>
 
<?php
	include("/var/www/html/common/admintemplate.php");
?>
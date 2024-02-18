<?php
	$RequireLogin = true;
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	
	$Customers = $site->Modules['Business']->GetCustomers();
	
	$invoice = LoadClass(SiteRoot . '/modules/classes/business/Invoice');
	
	if (array_key_exists('InvoiceID', $_GET)) {
		$InvoiceID = $_GET['InvoiceID'];
		$invoice->load($InvoiceID);
	}
	
	if (array_key_exists('SaveInvoice', $_POST)) {
		$invoice->updateAttributes($_POST);
		$invoice->save();
		header('Location: ' . $_SERVER['PHP_SELF'] . '?InvoiceID=' . $invoice->get('InvoiceID'));
	}
	
	ob_start();
?>
	<div><a href="/modules/business/admin/index.php">Back</a></div>
	<form method="post" enctype="multipart/form-data" name="" action="<?php echo $_SERVER['PHP_SELF']; echo '?'; echo $_SERVER['QUERY_STRING']; ?>">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>
					Customer:<br />
					<select name="CustomerID">
						<?php
							foreach($Customers AS $key => $value){
								$ThisCustomerID = $value->get('CustomerID');
								$ThisCustomerFirstName = $value->get('FirstName');
								if($ThisCustomerID == $invoice->get('CustomerID'))
								{
									echo '<option value="' . $ThisCustomerID . '" selected="selected">' . $ThisCustomerFirstName . '</option>';
								}else{
									echo '<option value="' . $ThisCustomerID . '">' . $ThisCustomerFirstName . '</option>';
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right"><input type="submit" name="SaveInvoice" value="Save" /></td>
			</tr>
		</table>
	</form>
<?php
	if(!$invoice->IsNewRecord()){
		$InvoiceItems = $invoice->GetInvoiceItems();
		
		echo '<a href="/modules/business/invoicetemplate.php?InvoiceID=' . $invoice->get('InvoiceID') . '" target="_blank">Print</a><br /><br />';
		
		echo '<a href="/modules/business/admin/invoiceitems/manage.php?InvoiceID=' . $invoice->get('InvoiceID') . '">Add Item</a>';
		
		echo '<h3>Items</h3>';
		
		foreach($InvoiceItems AS $key => $value){
			$ThisInvoiceItemID = $value->get('InvoiceItemID');
			$ThisDescription = $value->get('Description');
			
			echo '<a href="/modules/business/admin/invoiceitems/manage.php?InvoiceID=' . $invoice->get('InvoiceID') . '&InvoiceItemID=' . $ThisInvoiceItemID . '">' . $ThisDescription . '</a><br />';
		}
		
	}
?>
<?php
		$BodyContent = ob_get_contents();
	ob_end_clean();
?>
 
<?php
	include("/home/matt/websites/mandjscreations.com/common/admintemplate.php");
?>
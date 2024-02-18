<?php
	$RequireLogin = true;
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	
	$invoiceitem = LoadClass(SiteRoot . '/modules/classes/business/InvoiceItem');
	
	if (array_key_exists('InvoiceItemID', $_GET)) {
		$InvoiceItemID = $_GET['InvoiceItemID'];
		$invoiceitem->load($InvoiceItemID);
	}
	
	if (array_key_exists('SaveInvoiceItem', $_POST)) {
		$invoiceitem->set('InvoiceID',$_GET['InvoiceID']);
		$invoiceitem->updateAttributes($_POST);
		$invoiceitem->save();
		header('Location: ' . $_SERVER['PHP_SELF'] . '?InvoiceItemID=' . $invoiceitem->get('InvoiceItemID'));
	}
	
	ob_start();
?>
	<div><a href="/modules/business/admin/index.php">Back</a></div>
	<form method="post" enctype="multipart/form-data" name="" action="<?php echo $_SERVER['PHP_SELF']; echo '?'; echo $_SERVER['QUERY_STRING']; ?>">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>Description:<br /><input type="text" name="Description" value="<?php echo $invoiceitem->get('Description'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Price:<br /><input type="text" name="Price" value="<?php echo $invoiceitem->get('Price'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Time:<br /><input type="text" name="Time" value="<?php echo $invoiceitem->get('Time'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Total:<br /><input type="text" name="Total" value="<?php echo $invoiceitem->get('Total'); ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right"><input type="submit" name="SaveInvoiceItem" value="Save" /></td>
			</tr>
		</table>
	</form>
<?php
		$BodyContent = ob_get_contents();
	ob_end_clean();
?>
 
<?php
	include("/home/matt/websites/mandjscreations.com/common/admintemplate.php");
?>
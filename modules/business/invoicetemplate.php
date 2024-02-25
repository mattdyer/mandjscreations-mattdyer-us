<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	$invoice = LoadClass(SiteRoot . '/modules/classes/business/Invoice');
	$customer = LoadClass(SiteRoot . '/modules/classes/business/Customer');
	$invoice->load($_GET['InvoiceID']);
	$customer->load($invoice->get('CustomerID'));
	$invoiceitems = $invoice->GetInvoiceItems();
	$color = '#003';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Invoice</title>
<style type="text/css">
	body{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif;
		color:<?php echo $color; ?>;
	}
	h1{
		text-transform:uppercase;
	}
	#Header{
		text-align:left;
		font-size:28px;
		font-weight:bold;
	}
	#Header > div{
		display:inline-block;
		vertical-align:top;
	}
	#Header #Info{
		font-size:14px;
		vertical-align:top;
	}
	#Header img{
		
	}
	#CustomerInfo{
		margin:20px;
		padding:5px;
		border:1px solid <?php echo $color; ?>;
		height:60px;
		font-size:14px;
	}
	#ItemTable{
		border-collapse:collapse;
		margin:20px;
		width:690px;
	}
	#ItemTable th{
		border:1px solid <?php echo $color; ?>;
		background-color:#CCC;
		font-size:16px;
	}
	#ItemTable td{
		border:1px solid <?php echo $color; ?>;
		height:30px;
		padding:5px;
	}
	#TotalRow td{
		border:none;
	}
	#TotalRow th{
		background:none;
	}
</style>
</head>

<body>
<div style="margin:0px auto; width:730px;">
	<div id="Header">
		<div>
			<img src="/images/logo.jpg" />
		</div>
		<div>
			<div>M.A.D. Computing</div>
			<div id="Info">
				Phone: (406) 270-1483<br />
				Email: madmatt1220@gmail.com<br />
				Date: <?php echo $invoice->formatDate('DateEntered'); ?>
			</div>
		</div>
	</div>
	<h1>Customer</h1>
	<div id="CustomerInfo">
		<?php echo $customer->get('FirstName'); ?>
	</div>
	<h1>INVOICE</h1>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" id="ItemTable">
		<tr>
			<th style="width:50%;">Description</th>
			<th style="width:15%;">Price</th>
			<th style="width:15%;">Time</th>
			<th style="width:15%;">Total</th>
		</tr>
		<?php
			foreach($invoiceitems AS $key => $value){
		?>
		<tr>
			<td><?php echo $value->get('Description'); ?></td>
			<td><?php echo $value->get('Price'); ?></td>
			<td><?php echo $value->get('Time'); ?></td>
			<td>$<?php echo $value->get('Total'); ?></td>
		</tr>
		<?php
			}
		?>
		<?php
			for($i=1;$i<10;$i++){
		?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php
			}
		?>
		<tr id="TotalRow">
			<td></td>
			<td></td>
			<th>Total:</th>
			<th>$<?php echo $invoice->getTotal(); ?></th>
		</tr>
	</table>
</div>

</body>
</html>
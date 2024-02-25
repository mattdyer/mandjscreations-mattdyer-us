<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	if (array_key_exists('ContactUs', $_POST)) {
		ob_start();
		
		echo "Name: " . $_POST['Name'] . "\n\n";
		echo "Email: " . $_POST['Email'] . "\n\n";
		echo "Comments: " . $_POST['Comments'];
		
		$ContactFormContent = ob_get_contents();
		ob_end_clean();
		mail('madmatt1220@gmail.com','Contact Form Filled Out',$ContactFormContent,'From: matt@mandjscreations.com');
		header('Location: ' . $_SERVER['PHP_SELF'] . '?Contact=1');
	}
	
	ob_start();
	if (!array_key_exists('Contact', $_GET)) {
?>
	<h2>Contact Us</h2>
	<div>
		<p>If you would like any information regarding the content of the site or you have any questions please fill out the form below and we will get back to you soon.  Please see our <a href="/service/privacy.php">Privacy Policy</a> for more information.</p>
		<form method="post" enctype="multipart/form-data" name="ContactForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>Name:<br /><input type="text" name="Name" /></td>
					<td>Email:<br /><input type="text" name="Email" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">Comments:<br /><textarea name="Comments" style="height:300px; width:500px;" rows="1" cols="1"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" align="right"><input type="submit" name="ContactUs" value="Contact Us" /></td>
				</tr>
			</table>
		</form>
	</div>
<?php
	}else{
?>
	<h2>Contact Us</h2>
	<p>Thank you. We will contact you soon.</p>
<?php
	}
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
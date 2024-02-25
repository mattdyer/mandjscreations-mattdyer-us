<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	if (array_key_exists('ContactUs', $_POST)) {
		ob_start();
		
		echo "Name: " . $_POST['Name'] . "\n\n";
		echo "Email: " . $_POST['Email'] . "\n\n";
		echo "Phone: " . $_POST['Phone'] . "\n\n";
		echo "Comments: " . $_POST['Comments'];
		
		$ContactFormContent = ob_get_contents();
		ob_end_clean();
		mail('madmatt1220@gmail.com','Contact Form Filled Out',$ContactFormContent,'From: matt@mandjscreations.com');
		header('Location: ' . $_SERVER['PHP_SELF'] . '?Contact=1');
	}
	
	ob_start();
	echo '<div class="bodyContentSection">';
	if (!array_key_exists('Contact', $_GET)) {
?>
	<h2>Contact Us</h2>
	<div class="BreadCrumb">&gt; <a href="/">Home</a> &gt; Contact Us</div>
	<div class="pageSection">
		<p>Fill out the form below to contact us about getting your computer problems solved.  We are happy to answer your questions via email.  If you need further help you can give us a call or setup a time for a visit to your location.  Please see our <a href="/service/privacy.php">Privacy Policy</a> for more information.</p>
		<p style="font-size:16px; font-weight:bold; color:#00F;">Fill out the form below to contact via email.</p>
		<?php /*?><p style="font-size:16px; font-weight:bold; color:#00F;">We can be reached by phone at 406-270-1483.</p><?php */?>
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
					<td>Phone:<br /><input type="text" name="Phone" /></td>
					<td></td>
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
	<p class="pageSection">Thank you. We will contact you soon.</p>
<?php
	}
	echo '</div>';
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
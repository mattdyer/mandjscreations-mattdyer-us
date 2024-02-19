<?php
	include("/var/www/html/modules/AppInit.php");
	
	ob_start();
?>
	<h2>About Us</h2>
	<p>MAD Computing is a small technology company in Kalispell, MT. The founder of MAD Computing has been working on computers professionally since approximately 2004, and web design since 2006.</p>
	<p></p>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
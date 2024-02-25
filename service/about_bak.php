<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	ob_start();
?>
	<h2>About Us</h2>
	<p>The Repository was started by a small technology company in Kalispell, MT. Our goals include using the internet, and the worldwide connectivity it provides, to accelerate the pace of scientific advancement. A goal this lofty may never be fully realized, but many improvements could be made in the attempt.</p>
	<p></p>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
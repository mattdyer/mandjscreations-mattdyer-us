<?php
	include("/home/matt/websites/mandjscreations.com/modules/AppInit.php");
	
	ob_start();
		/* Content Goes Here */
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/admintemplate.php");
?>
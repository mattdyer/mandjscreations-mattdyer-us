<?php
	include("/var/www/html/modules/AppInit.php");
	
	ob_start();
?>

<script type="text/javascript">
	
</script>

<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
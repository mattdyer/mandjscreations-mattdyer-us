<?php
	$RequireLogin = true;
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	ob_start();
		echo '<div>This is the main admin page.</div>';
		//$user = unserialize($_SESSION['User']);
		echo $user->get('Email');
		echo '<br>';
		echo $user->get('Admin');
		$DateValues = getdate(time());
		
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/admintemplate.php");
?>
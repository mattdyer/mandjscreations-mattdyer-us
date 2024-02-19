<?php
	$RequireLogin = true;
	include("/var/www/html/modules/AppInit.php");
	
	$Users = $site->GetUsers();
	
	ob_start();
		?>
			<div><a href="/modules/admin/users/manage.php">Add New User</a></div>
		<?php
		foreach($Users AS $key => $value){
			$ThisUserID = $value->get('UserID');
			$ThisEmail = $value->get('Email');
			echo '<div><a href="/modules/admin/users/manage.php?UserID=' . $ThisUserID . '">' . $ThisEmail . '</a> - <a href="' . $_SERVER['PHP_SELF'] . '?DeleteUserID=' . $ThisUserID . '" onclick="return confirm(\'Are you sure you want to delete this user?\');">Delete</a></div>';
		}
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/admintemplate.php");
?>
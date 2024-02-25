<?php
	$RequireLogin = true;
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	$user = LoadClass(SiteRoot . '/modules/classes/User');
	
	if (array_key_exists('UserID', $_GET)){
		$user->load($_GET['UserID']);
	}
	
	if (array_key_exists('SaveUser', $_POST)){
		$user->set('SiteID',$site->get('SiteID'));
		$user->set('Email',$_POST['Email']);
		$user->save();
		if(strlen($_POST['NewPassword'])){
			$user->SetPassword($_POST['NewPassword']);
		}
		header('Location: ' . $_SERVER['PHP_SELF'] . '?UserID=' . $user->get('UserID'));
	}
	
	ob_start();
		?>
			<div><a href="/modules/admin/users/index.php">Back</a></div>
			<form method="post" enctype="multipart/form-data" name="" action="<?php echo $_SERVER['PHP_SELF']; echo '?'; echo $_SERVER['QUERY_STRING']; ?>">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>Email:<br /><input type="text" name="Email" value="<?php echo $user->get('Email'); ?>" /></td>
					</tr>
					<tr>
						<td>Password:<br /><input type="password" name="NewPassword" /></td>
					</tr>
					<tr>
						<td><input type="submit" name="SaveUser" value="Save" /></td>
					</tr>
				</table>
			</form>
		<?php
		$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/admintemplate.php");
?>